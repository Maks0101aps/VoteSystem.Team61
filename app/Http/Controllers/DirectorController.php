<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DirectorController extends Controller
{
    public function index()
    {
        return Inertia::render('Director/Petitions/Index', [
            'petitions' => Petition::with(['user.schoolClass', 'schoolClass', 'signatures'])->where('status', 'pending_review')->get()->map(function ($petition) {
            return [
                'id' => $petition->id,
                'title' => $petition->title,
                'description' => $petition->description,
                'signatures_count' => $petition->signatures->count(),
                'signatures_required' => $petition->signatures_required,
                'created_at' => $petition->created_at,
                'ends_at' => $petition->ends_at,
                'user' => $petition->user,
                'school_class' => $petition->schoolClass,
            ];
        }),
        ]);
    }

    public function approve(Petition $petition)
    {
        $petition->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Petition approved.');
    }

    public function reject(Petition $petition)
    {
        $petition->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Petition rejected.');
    }
    //
}
