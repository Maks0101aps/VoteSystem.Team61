<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Voting/Create', [
            'title' => __('voting.create_title'),
            'roles' => collect(['student', 'parent', 'teacher'])->mapWithKeys(fn ($role) => [$role => __('roles.'.$role)]),
            'classes' => range(1, 11),
            'class_letters' => ['а', 'б', 'в', 'г'],
            'duration_options' => [
                2 => '2 хвилини',
                5 => '5 хвилин',
                15 => '15 хвилин',
                30 => '30 хвилин',
                60 => '1 година',
                300 => '5 годин',
                1440 => '1 день',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'duration' => ['required', 'integer', 'in:2,5,15,30,60,300,1440'],
            'for_all' => ['required', 'boolean'],
            'roles' => ['required_if:for_all,false', 'array'],
            'roles.*' => ['in:student,parent,teacher'],
            'class' => ['nullable', 'integer'],
            'class_letter' => ['nullable', 'string', 'max:1'],
        ]);

        $ends_at = $request->duration ? now()->addMinutes((int)$request->duration) : null;

        $voting = Voting::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'ends_at' => $ends_at,
        ]);

        if ($request->for_all) {
            VotingVisibility::create([
                'voting_id' => $voting->id,
                'role' => 'all',
            ]);
        } else {
            if (in_array('student', $request->roles)) {
                $request->validate([
                    'class' => ['required', 'integer'],
                    'class_letter' => ['required', 'string', 'max:1'],
                ]);
            }

            foreach ($request->roles as $role) {
                VotingVisibility::create([
                    'voting_id' => $voting->id,
                    'role' => $role,
                    'class_number' => ($role === 'student') ? $request->class : null,
                    'class_letter' => ($role === 'student') ? $request->class_letter : null,
                ]);
            }
        }

        return Redirect::route('voting.index')->with('success', 'Voting created.');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
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

                                if ($user->role === 'student') {
                                    $roleSpecificQuery->where(function ($classQuery) use ($user) {
                                        $classQuery->whereNull('class_number')
                                            ->orWhere(function ($specificClassQuery) use ($user) {
                                                $specificClassQuery->where('class_number', $user->school_class_id)
                                                                   ->where('class_letter', $user->class_letter);
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