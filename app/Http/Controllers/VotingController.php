<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VotingController extends Controller
{
    public function index()
    {
        return Inertia::render('Voting/Index', [
            'title' => 'Голосування',
        ]);
    }
} 