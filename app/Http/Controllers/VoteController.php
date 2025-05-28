<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VoteController extends Controller
{
    public function showBallot(Election $election)
    {
        // Verify voter eligibility
        $voter = Auth::user()->voterProfile;

        if (!$voter->is_approved) {
            return redirect()->route('home')->with('error', 'Your profile is not approved for voting.');
        }

        // Verify constituency match
        if ($voter->constituency !== $election->constituency) {
                return redirect()->route('home')
                       ->with('error', 'You cannot vote in this election because you are registered in '.$voter->constituency.' constituency.');
            }

        if (Vote::where('voter_profile_id', $voter->id)
              ->where('election_id', $election->id)
              ->exists()) {
            return redirect()->route('home')->with('error', 'You have already voted in this election.');
        }

        // Only show candidates in voter's constituency
        $candidates = Candidate::where('election_id', $election->id)
                      ->where('constituency', $voter->constituency) // Match voter's constituency
                      ->where('approved', true)
                      ->where('status', 'Active')
                      ->get();

        return view('votes.ballot', [
            'election' => $election,
            'candidates' => $candidates,
            'voterConstituency' => $voter->constituency // Pass to view for verification
        ]);
    }

    public function submit(Request $request, Election $election)
    {
        $voter = Auth::user()->voterProfile;

       // Verify constituency match with clear error
           if ($voter->constituency !== $election->constituency) {
               return back()->with('error',
                   'Voting restricted! You are registered in '.$voter->constituency.
                   ' constituency, not '.$election->constituency.'.');
           }
           // Time validation
            if (!$election->isActive()) {
                   return back()->with('error', 'Voting is not active for this election');
               }


        // Time validation (using EAT)
        $now = now('Africa/Nairobi');
        $startDate = $election->start_date->timezone('Africa/Nairobi');
        $endDate = $election->end_date->timezone('Africa/Nairobi');

        if ($now < $startDate) {
            return back()->with('error', 'Voting starts at '.$startDate->format('M d, Y H:i').' EAT');
        }

        if ($now > $endDate) {
            return back()->with('error', 'Voting ended at '.$endDate->format('M d, Y H:i').' EAT');
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id'
        ]);

        $candidate = Candidate::find($request->candidate_id);

        // Verify candidate is in voter's constituency
        if ($candidate->constituency !== $voter->constituency) {
            return back()->with('error', 'You can only vote for candidates in your constituency.');
        }

        // Verify candidate is in this election
        if ($candidate->election_id != $election->id) {
            return back()->with('error', 'Invalid candidate selection for this election.');
        }

        // Check for existing vote
        if (Vote::where('voter_profile_id', $voter->id)
              ->where('election_id', $election->id)
              ->exists()) {
            return back()->with('error', 'You have already voted in this election.');
        }

        // Record the vote
        Vote::create([
            'voter_profile_id' => $voter->id,
            'election_id' => $election->id,
            'candidate_id' => $candidate->id,
            'vote_type' => $election->type,
            'constituency' => $voter->constituency, // Use voter's constituency
            'ip_address' => $request->ip(),
            'device_id' => $request->header('User-Agent'),
            'voted_at' => now('UTC')
        ]);

        return redirect()->route('votes.confirmation')
               ->with('success', 'Your vote has been recorded!');
    }

    public function confirmation()
    {
        return view('votes.confirmation');
    }
}
