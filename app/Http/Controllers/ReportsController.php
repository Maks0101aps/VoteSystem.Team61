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
        $votings = Voting::with(['options' => function ($query) {
            $query->withCount('user_votes');
        }])->get();

        $petitionsLastMonth = Petition::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $allPetitions = Petition::withCount('signatures')->get();
        $totalPetitions = $allPetitions->count();
        $acceptedPetitions = $allPetitions->filter(function ($petition) {
            return $petition->signatures_count >= $petition->signatures_required;
        })->count();

        $acceptancePercentage = $totalPetitions > 0 ? round(($acceptedPetitions / $totalPetitions) * 100, 2) : 0;
        $totalVotings = $votings->count();

        return Inertia::render('Reports/Index', [
            'votings' => $votings,
            'stats' => [
                'petitionsLastMonth' => $petitionsLastMonth,
                'acceptancePercentage' => $acceptancePercentage,
                'totalVotings' => $totalVotings,
            ],
        ]);
    }
}
