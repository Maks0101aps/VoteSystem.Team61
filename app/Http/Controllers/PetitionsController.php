<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use App\Models\PetitionSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PetitionsController extends Controller
{
    public function index()
    {
        $petitions = Petition::with(['signatures', 'user'])
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
                    'created_at' => $petition->created_at->format('d.m.Y'),
                    'author' => $petition->user->name,
                    'is_signed' => $petition->signatures->contains('user_id', Auth::id()),
                    'is_completed' => $petition->signatures_count >= $petition->signatures_required,
                ];
            });

        return Inertia::render('Petitions/Index', [
            'title' => 'Петиції',
            'petitions' => $petitions,
        ]);
    }

    public function create()
    {
        return Inertia::render('Petitions/Create', [
            'title' => 'Створити петицію',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:100'],
            'description' => ['required'],
            'signatures_required' => ['required', 'integer', 'min:10'],
        ]);

        $petition = Petition::create([
            'title' => $request->title,
            'description' => $request->description,
            'signatures_required' => $request->signatures_required,
            'user_id' => Auth::id(),
        ]);

        return Redirect::route('petitions')->with('success', 'Петиція успішно створена.');
    }

    public function sign(Petition $petition)
    {
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
} 