<?php

namespace App\Http\Controllers;

use App\Events\NewVotingCreated;
use App\Models\Voting;
use App\Models\VotingVisibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VotingController extends Controller
{
    public function create()
    {
        // Using direct string instead of translation key
        $locale = app()->getLocale();
        $title = $locale === 'uk' ? 'Створити голосування' : 'Create Voting';

        return Inertia::render('Voting/Create', [
            'title' => $title,
            'duration_options' => [
                120 => '2 години',
                360 => '6 годин',
                720 => '12 годин',
                1440 => '1 день',
                4320 => '3 дні',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'duration' => ['required', 'integer', 'in:120,360,720,1440,4320'],
            'target_type' => ['required', 'string', 'in:school,class'],
        ]);

        $user = Auth::user();

        $ends_at = $request->duration ? now()->addMinutes((int)$request->duration) : null;

        $voting = Voting::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $user->id,
            'ends_at' => $ends_at,
        ]);

        if ($request->target_type === 'school') {
            VotingVisibility::create([
                'voting_id' => $voting->id,
                'role' => 'all',
            ]);
        } else {
            if (!$user->school_class_id) {
                return Redirect::back()->with('error', 'Ви не прив\'язані до класу, тому не можете створювати голосування для класу.');
            }
            $user->load('schoolClass');
            VotingVisibility::create([
                'voting_id' => $voting->id,
                'role' => 'student',
                'class_number' => $user->schoolClass->class_number,
                'class_letter' => $user->schoolClass->class_letter,
            ]);
        }

        // Загружаем связанные данные перед отправкой события
        $voting->load(['user', 'visibilities']);
        
        // Отправляем событие о создании нового голосования
        try {
            event(new NewVotingCreated($voting));
        } catch (\Exception $e) {
            // Логируем ошибку, но не мешаем основному процессу
            \Log::error('Ошибка отправки события: ' . $e->getMessage());
        }

        return Redirect::route('voting.index')->with('success', 'Голосування успішно створено.');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'student') {
            $user->load('schoolClass');
        }

        $filters = $request->only('filter', 'trashed');
        $filterValue = $filters['filter'] ?? 'all';

        $votingsQuery = Voting::query();

        if (isset($filters['trashed']) && $filters['trashed'] === 'only') {
            $votingsQuery->onlyTrashed();
        }

        if ($user->role !== 'director') {
            $votingsQuery->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('visibilities', function ($subQuery) use ($user) {
                        $subQuery->where('role', 'all')
                            ->orWhere(function ($roleSpecificQuery) use ($user) {
                                $roleSpecificQuery->where('role', $user->role);

                                if ($user->role === 'student' && $user->schoolClass) {
                                    $roleSpecificQuery->where(function ($classQuery) use ($user) {
                                        $classQuery->whereNull('class_number')
                                            ->orWhere(function ($specificClassQuery) use ($user) {
                                                $specificClassQuery->where('class_number', $user->schoolClass->class_number)
                                                                   ->where('class_letter', $user->schoolClass->class_letter);
                                            });
                                    });
                                }
                            });
                    });
            });
        }

        if ($filterValue === 'active') {
            $votingsQuery->where(function ($query) {
                $query->where('ends_at', '>', now())
                      ->orWhereNull('ends_at');
            });
        } elseif ($filterValue === 'completed') {
            $votingsQuery->whereNotNull('ends_at')->where('ends_at', '<=', now());
        } elseif ($filterValue === 'my') {
            $votingsQuery->where('user_id', $user->id);
        }

        $votings = $votingsQuery->with(['user', 'votes' => fn ($q) => $q->where('user_id', $user->id), 'visibilities', 'comments.user'])
            ->withCount([
                'votes as votes_for_count' => fn ($q) => $q->where('choice', 'for'),
                'votes as votes_against_count' => fn ($q) => $q->where('choice', 'against'),
                'votes as votes_abstain_count' => fn ($q) => $q->where('choice', 'abstain'),
                'comments as comments_count',
            ])
            ->latest()
            ->get();

        return Inertia::render('Voting/Index', [
            'title' => 'Голосування',
            'server_time' => now()->toIso8601String(),
            'votings' => $votings->map(fn (Voting $voting) => [
                'id' => $voting->id,
                'title' => $voting->title,
                'description' => $voting->description,
                'user' => $voting->user ? $voting->user->only('id', 'first_name', 'last_name') : null,
                'created_at' => $voting->created_at->toIso8601String(),
                'ends_at' => $voting->ends_at ? $voting->ends_at->toIso8601String() : null,
                'user_vote' => $voting->votes->first()->choice ?? null,
                'votes_for_count' => $voting->votes_for_count,
                'votes_against_count' => $voting->votes_against_count,
                'votes_abstain_count' => $voting->votes_abstain_count,
                'comments_count' => $voting->comments_count,
                'visibility' => $voting->visibilities,
                'deleted_at' => $voting->deleted_at,
                'comments' => $voting->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'user_name' => $comment->user->name,
                    ];
                }),
                'commentable_type' => 'App\Models\Voting',
            ]),
            'filters' => $filters,
            'auth' => ['user' => $user->only('id', 'first_name', 'last_name')],
        ]);
    }

    public function vote(Request $request, Voting $voting)
    {
        if ($voting->ends_at && $voting->ends_at->isPast()) {
            return Redirect::route('voting.index')->with('error', 'Voting has ended.');
        }

        $request->validate([
            'choice' => ['required', 'in:for,against,abstain'],
        ]);

        $voting->votes()->create([
            'user_id' => Auth::id(),
            'choice' => $request->choice,
        ]);

        return Redirect::route('voting.index')->with('success', 'Your vote has been cast.');
    }

    public function destroy(Voting $voting)
    {
        if (Auth::id() !== $voting->user_id) {
            return Redirect::back()->with('error', 'You are not authorized to delete this voting.');
        }

        $voting->delete();

        return Redirect::route('voting.index')->with('success', 'Voting deleted.');
    }

    public function restore(Voting $voting)
    {
        if (Auth::id() !== $voting->user_id) {
            return Redirect::back()->with('error', 'You are not authorized to restore this voting.');
        }

        $voting->restore();

        return Redirect::back()->with('success', 'Voting restored.');
    }


} 