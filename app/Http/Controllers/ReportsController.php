<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use App\Models\Voting;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class ReportsController extends Controller
{
    public function index(): Response
    {
        // Stats for the last month
        $votingsLastMonth = Voting::withTrashed()->where('created_at', '>=', Carbon::now()->subMonth())->count();
        $petitionsLastMonth = Petition::where('created_at', '>=', Carbon::now()->subMonth())->count();

        $allPetitionsLastMonth = Petition::withCount('signatures')->where('created_at', '>=', Carbon::now()->subMonth())->get();
        $totalPetitionsLastMonth = $allPetitionsLastMonth->count();
        $acceptedPetitionsLastMonth = $allPetitionsLastMonth->filter(function ($petition) {
            return $petition->signatures_count >= $petition->signatures_required;
        })->count();

        $acceptancePercentage = $totalPetitionsLastMonth > 0 ? round(($acceptedPetitionsLastMonth / $totalPetitionsLastMonth) * 100, 2) : 0;

        // All-time stats
        $totalUsers = \App\Models\User::count();
        $totalVotesCast = \App\Models\Vote::count();


        return Inertia::render('Reports/Index', [
            'stats' => [
                // last month stats
                'petitionsLastMonth' => $petitionsLastMonth,
                'acceptancePercentage' => $acceptancePercentage,
                'votingsLastMonth' => $votingsLastMonth,

                // overall stats
                'totalUsers' => $totalUsers,
                'totalVotesCast' => $totalVotesCast,
            ],
        ]);
    }
}
