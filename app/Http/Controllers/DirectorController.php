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
            'petitions' => Petition::with('user')->where('status', 'pending_review')->get(),
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
