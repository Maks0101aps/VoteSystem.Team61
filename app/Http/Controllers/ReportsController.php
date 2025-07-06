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
        $totalVotings = Voting::withTrashed()->where('created_at', '>=', Carbon::now()->subMonth())->count();

        $petitionsLastMonth = Petition::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $allPetitions = Petition::withCount('signatures')->where('created_at', '>=', Carbon::now()->subMonth())->get();
        $totalPetitions = $allPetitions->count();
        $acceptedPetitions = $allPetitions->filter(function ($petition) {
            return $petition->signatures_count >= $petition->signatures_required;
        })->count();

        $acceptancePercentage = $totalPetitions > 0 ? round(($acceptedPetitions / $totalPetitions) * 100, 2) : 0;

        return Inertia::render('Reports/Index', [
            'stats' => [
                'petitionsLastMonth' => $petitionsLastMonth,
                'acceptancePercentage' => $acceptancePercentage,
                'totalVotings' => $totalVotings,
            ],
        ]);
    }
}
