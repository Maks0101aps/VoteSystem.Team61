<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        
        return Inertia::render('Dashboard/Index', [
            'guestUser' => $user ? false : true
        ]);
    }
}
