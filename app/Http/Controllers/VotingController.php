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
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'for_all' => ['required', 'boolean'],
            'roles' => ['required_if:for_all,false', 'array'],
            'roles.*' => ['in:student,parent,teacher'],
            'class' => ['nullable', 'integer'],
            'class_letter' => ['nullable', 'string', 'max:1'],
        ]);

        $voting = Voting::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        $rolesToStore = $request->for_all ? ['student', 'parent', 'teacher'] : $request->roles;

        if (in_array('student', $rolesToStore)) {
            $request->validate([
                'class' => ['required', 'integer'],
                'class_letter' => ['required', 'string', 'max:1'],
            ]);
        }

        foreach ($rolesToStore as $role) {
            VotingVisibility::create([
                'voting_id' => $voting->id,
                'role' => $role,
                'class_number' => $role === 'student' ? $request->class : null,
                'class_letter' => $role === 'student' ? $request->class_letter : null,
            ]);
        }

        return Redirect::route('voting.index')->with('success', 'Voting created.');
    }

    public function index()
    {
        $user = Auth::user();

        $votingsQuery = Voting::query();

        $votingsQuery->whereHas('visibilities', function ($query) use ($user) {
            $query->where('role', $user->role);

            if ($user->role === 'student') {
                $query->where(function ($q) use ($user) {
                    $q->whereNull('class_number')
                        ->orWhere(function ($q2) use ($user) {
                            $q2->where('class_number', $user->school_class_id)
                                ->where('class_letter', $user->class_letter);
                        });
                });
            }
        });

        return Inertia::render('Voting/Index', [
            'title' => 'Голосування',
            'votings' => $votingsQuery->with('user')
                ->latest()
                ->get()
                ->map(fn (Voting $voting) => [
                    'id' => $voting->id,
                    'title' => $voting->title,
                    'description' => $voting->description,
                    'user' => $voting->user ? $voting->user->only('id', 'first_name', 'last_name') : null,
                    'created_at' => $voting->created_at->diffForHumans(),
                ]),
        ]);
    }

} 