<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use App\Models\Voting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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


                // History data
        $user = Auth::user();

        // History data
        $user = Auth::user();

        $mapItem = function($item, $type, $action_type) {
            $status = $item->status;
            if ($type === 'petition' && $status === 'active' && $item->ends_at->isPast()) {
                $status = 'expired';
            } elseif ($type === 'voting') {
                $status = $item->deleted_at ? 'archived' : ($item->ends_at && $item->ends_at->isPast() ? 'closed' : 'active');
            }

            return [
                'id' => $item->id,
                'title' => $item->title,
                'type' => $type,
                'action_type' => $action_type,
                'created_at_formatted' => $item->created_at->format('d.m.Y H:i'),
                'status' => $status,
            ];
        };

        // Created Votings
        $createdVotings = Voting::where('user_id', $user->id)->withTrashed()->latest()->get()->map(fn($item) => $mapItem($item, 'voting', 'created'));

        // Created Petitions
        $createdPetitions = Petition::where('user_id', $user->id)->latest()->get()->map(fn($item) => $mapItem($item, 'petition', 'created'));

        // Participated Votings
        $participatedVotings = Voting::whereHas('votes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('user_id', '!=', $user->id)->withTrashed()->latest()->get()->map(fn($item) => $mapItem($item, 'voting', 'participated'));

        // Participated Petitions
        $participatedPetitions = Petition::whereHas('signatures', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('user_id', '!=', $user->id)->latest()->get()->map(fn($item) => $mapItem($item, 'petition', 'participated'));

        return Inertia::render('Reports/Index', [
            'title' => 'reports_page.title',
            'stats' => [
                // last month stats
                'petitionsLastMonth' => $petitionsLastMonth,
                'acceptancePercentage' => $acceptancePercentage,
                'votingsLastMonth' => $votingsLastMonth,

                // overall stats
                'totalUsers' => $totalUsers,
                'totalVotesCast' => $totalVotesCast,
            ],
            'createdVotings' => $createdVotings,
            'createdPetitions' => $createdPetitions,
            'participatedVotings' => $participatedVotings,
            'participatedPetitions' => $participatedPetitions,
        ]);
    }
}
