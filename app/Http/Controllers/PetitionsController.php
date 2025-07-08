<?php

namespace App\Http\Controllers;

use App\Events\NewPetitionCreated;
use App\Models\Petition;
use App\Models\PetitionSignature;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PetitionsController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('filter');
        $filterValue = $filters['filter'] ?? 'all';

        $user = Auth::user();

        $petitionsQuery = Petition::with(['signatures', 'user', 'schoolClass', 'comments.user'])
            ->withCount(['signatures', 'comments'])
            ->latest();

        if ($user->role === 'student') {
            $petitionsQuery->where(function ($query) use ($user) {
                $query->whereNull('school_class_id') // Petitions for the whole school
                    ->orWhere('school_class_id', $user->school_class_id); // Petitions for the student's class
            });
        }

        if ($filterValue === 'active') {
            $petitionsQuery->where('status', 'active')->where('ends_at', '>', now());
        } elseif ($filterValue === 'completed') {
            $petitionsQuery->where(function ($query) {
                $query->whereIn('status', ['pending', 'approved', 'rejected'])
                    ->orWhere('ends_at', '<=', now());
            });
        }

        $petitions = $petitionsQuery->get()
            ->map(function ($petition) {
                return [
                    'id' => $petition->id,
                    'title' => $petition->title,
                    'description' => $petition->description,
                    'signatures_required' => $petition->signatures_required,
                    'signatures_count' => $petition->signatures_count,
                    'duration' => $petition->duration,
                    'ends_at' => $petition->ends_at->format('d.m.Y H:i'),
                    'created_at' => $petition->created_at->format('d.m.Y'),
                    'author' => $petition->user->name,
                    'is_signed' => $petition->signatures->contains('user_id', Auth::id()),
                    'status' => $petition->status,
                    'target_class' => $petition->schoolClass ? $petition->schoolClass->name : 'Для всієї школи',
                    'user_id' => $petition->user_id,
                    'comments_count' => $petition->comments_count,
                    'comments' => $petition->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at->diffForHumans(),
                            'user_name' => $comment->user->name,
                        ];
                    }),
                    'commentable_type' => 'App\Models\Petition',
                ];
            });

        return Inertia::render('Petitions/Index', [
            'title' => 'Петиції',
            'petitions' => $petitions,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        if (Auth::user()->role !== 'student') {
            return Redirect::route('petitions')->with('error', 'Тільки студенти можуть створювати петиції.');
        }

        // Using direct string instead of translation key
        $locale = app()->getLocale();
        $title = $locale === 'uk' ? 'Створити петицію' : 'Create Petition';

        return Inertia::render('Petitions/Create', [
            'title' => $title,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'student') {
            abort(403, 'У вас немає прав для створення петицій.');
        }

        $request->validate([
            'title' => ['required', 'max:100'],
            'description' => ['required'],
            'signatures_required' => ['required', 'integer', 'min:15'],
            'duration' => ['required', 'integer', 'in:24,48,72'],
            'target_type' => ['required', 'string', 'in:school,class'],
        ]);

        $user = Auth::user();
        $school_class_id = null;

        if ($request->target_type === 'class') {
            if (!$user->school_class_id) {
                return Redirect::back()->with('error', 'Ви не прив\'язані до класу, тому не можете створювати петиції для класу.');
            }
            $school_class_id = $user->school_class_id;
        }

        $petition = Petition::create([
            'title' => $request->title,
            'description' => $request->description,
            'signatures_required' => $request->signatures_required,
            'user_id' => $user->id,
            'duration' => $request->duration,
            'school_class_id' => $school_class_id,
        ]);

        // Загружаем связанные данные перед отправкой события
        $petition->load(['user', 'schoolClass']);
        
        // Отправляем событие о создании новой петиции
        try {
            event(new NewPetitionCreated($petition));
        } catch (\Exception $e) {
            // Логируем ошибку, но не мешаем основному процессу
            \Log::error('Ошибка отправки события петиции: ' . $e->getMessage());
        }

        return Redirect::route('petitions')->with('success', 'Петицію успішно створено.');
    }

    public function sign(Petition $petition)
    {
        if (Auth::user()->role !== 'student') {
            return Redirect::back()->with('error', 'Тільки студенти можуть підписувати петиції.');
        }

        // Check if user already signed
        $exists = PetitionSignature::where('petition_id', $petition->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$exists) {
            PetitionSignature::create([
                'petition_id' => $petition->id,
                'user_id' => Auth::id(),
            ]);

            $signaturesCount = PetitionSignature::where('petition_id', $petition->id)->count();

            if ($signaturesCount >= $petition->signatures_required) {
                $petition->update(['status' => 'pending']);
            }

            return Redirect::back()->with('success', 'Ви успішно підписали петицію.');
        }

        return Redirect::back()->with('error', 'Ви вже підписали цю петицію.');
    }

    public function destroy(Petition $petition)
    {
        if (Auth::id() !== $petition->user_id) {
            return Redirect::back()->with('error', 'Ви не можете видалити цю петицію.');
        }

        $petition->delete();

        return Redirect::route('petitions')->with('success', 'Петиція успішно видалена.');
    }
}
