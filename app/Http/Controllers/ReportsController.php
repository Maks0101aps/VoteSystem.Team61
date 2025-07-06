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

        $votings = Voting::where('user_id', $user->id)
            ->orWhereHas('votes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('user')
            ->withTrashed()
            ->latest()
            ->get()
            ->map(function ($voting) {
                return [
                    'id' => $voting->id,
                    'title' => $voting->title,
                    'type' => 'voting',
                    'created_at' => $voting->created_at,
                    'status' => $voting->deleted_at ? 'archived' : ($voting->ends_at && $voting->ends_at->isPast() ? 'closed' : 'active'),
                ];
            });

        $petitions = Petition::where('user_id', $user->id)
            ->orWhereHas('signatures', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($petition) {
                $status = $petition->status;
                if ($status === 'active' && $petition->ends_at->isPast()) {
                    $status = 'expired';
                }

                return [
                    'id' => $petition->id,
                    'title' => $petition->title,
                    'type' => 'petition',
                    'created_at' => $petition->created_at,
                    'status' => $status,
                ];
            });

        $history = $votings->concat($petitions)->sortByDesc('created_at')->values();

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
            'history' => $history->map(function($item) {
                $item['created_at_formatted'] = $item['created_at']->format('d.m.Y H:i');
                return $item;
            }),
        ]);
    }
}
