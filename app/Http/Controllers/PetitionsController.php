<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use App\Models\PetitionSignature;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PetitionsController extends Controller
{
    public function index()
    {
        $petitions = Petition::with(['signatures', 'user', 'schoolClass'])
            ->withCount('signatures')
            ->latest()
            ->get()
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
                    'is_completed' => $petition->signatures_count >= $petition->signatures_required,
                    'target_class' => $petition->schoolClass ? $petition->schoolClass->name : 'Вся школа',
                    'user_id' => $petition->user_id,
                ];
            });

        return Inertia::render('Petitions/Index', [
            'title' => 'Петиції',
            'petitions' => $petitions,
        ]);
    }

    public function create()
    {
        if (Auth::user()->role !== 'student') {
                        return Redirect::route('petitions')->with('error', 'Тільки учні можуть створювати петиції.');
        }

        $classData = SchoolClass::all()->groupBy('class_number')->map(function ($group) {
            return $group->pluck('class_letter');
        });

        return Inertia::render('Petitions/Create', [
            'title' => 'Створити петицію',
            'classData' => $classData,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'student') {
            abort(403, 'Тільки учні можуть створювати петиції.');
        }

        $request->validate([
            'title' => ['required', 'max:100'],
            'description' => ['required'],
            'signatures_required' => ['required', 'integer', 'min:10'],
            'duration' => ['required', 'integer', 'in:24,48,72'],
            'target_type' => ['required', 'string', 'in:school,class'],
            'class_number' => ['required_if:target_type,class', 'integer', 'max:11'],
            'class_letter' => ['required_if:target_type,class', 'string', 'size:1'],
        ]);

        $school_class_id = null;
        if ($request->target_type === 'class') {
            $schoolClass = SchoolClass::where('class_number', $request->class_number)
                ->where('class_letter', $request->class_letter)
                ->first();

            if (!$schoolClass) {
                return back()->withErrors(['class_letter' => 'Клас не знайдено.'])->withInput();
            }
            $school_class_id = $schoolClass->id;
        }

        $petition = Petition::create([
            'title' => $request->title,
            'description' => $request->description,
            'signatures_required' => $request->signatures_required,
            'user_id' => Auth::id(),
            'duration' => $request->duration,
            'school_class_id' => $school_class_id,
        ]);

        return Redirect::route('petitions')->with('success', 'Петиція успішно створена.');
    }

    public function sign(Petition $petition)
    {
        if (Auth::user()->role !== 'student') {
            return Redirect::back()->with('error', 'Тільки учні можуть підписувати петиції.');
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
