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
            'title' => 'Створення голосування',
            'roles' => ['student', 'parent', 'teacher'],
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
        return Inertia::render('Voting/Index', [
            'title' => 'Голосування',
            'votings' => [], // Pass an empty array for now
        ]);
    }

} 