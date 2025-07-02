<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PetitionsController extends Controller
{
    public function index()
    {
        return Inertia::render('Petitions/Index', [
            'title' => 'Петиції',
        ]);
    }
} 