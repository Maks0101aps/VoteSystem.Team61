<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VotingController extends Controller
{
    public function create()
    {
        return Inertia::render('Voting/Create', [
            'title' => 'Створення голосування',
            'roles' => ['student', 'parent', 'teacher', 'director'],
            'classes' => range(1, 11),
            'class_letters' => ['а', 'б', 'в', 'г'],
        ]);
    }

    public function index()
    {
        return Inertia::render('Voting/Index', [
            'title' => 'Голосування',
            'votings' => [], // Pass an empty array for now
        ]);
    }

} 