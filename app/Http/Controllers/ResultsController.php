<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;
use App\Models\ElectionResult;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    // Admin analytics dashboard
    public function analytics()
    {
        $elections = Election::with(['results' => function($query) {
            $query->orderBy('votes_count', 'desc');
        }])->get();

        // Constituency voting statistics
        $constituencyStats = Vote::selectRaw('
            constituency,
            COUNT(*) as total_votes,
            COUNT(DISTINCT voter_profile_id) as unique_voters
        ')
        ->groupBy('constituency')
        ->orderBy('total_votes', 'desc')
        ->get();

        return view('admin.results.analytics', compact('elections', 'constituencyStats'));
    }

    // Calculate and store results with proper ranking
    public function calculateResults(Election $election)
    {
        // First delete all existing results for a clean recalculation
        ElectionResult::where('election_id', $election->id)->delete();

        // Get fresh vote counts per candidate including any new votes
        $voteResults = Vote::where('election_id', $election->id)
            ->selectRaw('candidate_id, COUNT(*) as votes_count')
            ->groupBy('candidate_id')
            ->orderBy('votes_count', 'desc')
            ->get();

        // Calculate positions with proper tie handling
        $position = 1;
        $previousVotes = null;
        $skipPosition = 0;

        foreach ($voteResults as $result) {
            // Handle ties - candidates with same vote count get same position
            if ($previousVotes !== null && $previousVotes == $result->votes_count) {
                $skipPosition++;
            } else {
                $position += $skipPosition;
                $skipPosition = 0;
                $position = ($previousVotes !== null && $previousVotes > $result->votes_count)
                    ? $position + 1
                    : $position;
            }

            ElectionResult::create([
                'election_id' => $election->id,
                'candidate_id' => $result->candidate_id,
                'votes_count' => $result->votes_count,
                'position' => $position,
                'is_winner' => $position === 1 // Only first position is winner
            ]);

            $previousVotes = $result->votes_count;
        }

        return back()->with([
            'success' => 'Results recalculated successfully!',
            'recalculated' => true,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    // Public results view
    public function showResults(Election $election)
    {
        $results = ElectionResult::with('candidate')
            ->where('election_id', $election->id)
            ->orderBy('position')
            ->get();

        $totalVotes = $results->sum('votes_count');
        $voterTurnout = Vote::where('election_id', $election->id)
            ->distinct('voter_profile_id')
            ->count();

        return view('public.results.show', compact('election', 'results', 'totalVotes', 'voterTurnout'));
    }

    // Voter dashboard with upcoming and completed elections
    public function index()
    {
        $elections = Election::where('start_date', '>', now()) // Upcoming elections
                      ->orWhere(function($query) {
                          $query->where('start_date', '<=', now())
                                ->where('end_date', '>=', now());
                      }) // Active elections
                      ->orderBy('start_date')
                      ->get();

        $completedElections = Election::where('end_date', '<', now()) // Completed elections
                              ->has('results') // Only those with results
                              ->orderBy('end_date', 'desc')
                              ->get();

        return view('voter.dashboard', [
            'elections' => $elections,
            'completedElections' => $completedElections,
        ]);
    }

}
