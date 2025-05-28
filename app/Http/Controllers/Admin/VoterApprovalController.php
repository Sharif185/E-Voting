<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoterProfile;
use App\Models\User;
use Illuminate\Http\Request;

class VoterApprovalController extends Controller
{
    public function index()
    {
        $pendingVoters = VoterProfile::with('user')
            ->where('is_approved', false)
            ->get();

        return view('admin.voter-approval', compact('pendingVoters'));
    }

    public function approve(VoterProfile $voterProfile)
    {
        $voterProfile->update(['is_approved' => true]);
        return back()->with('status', 'Voter approved successfully');
    }

    public function approved()
    {
        $approvedVoters = VoterProfile::with('user')
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.voters.approved', compact('approvedVoters'));
    }

    public function revoke(VoterProfile $voterProfile)
    {
        $voterProfile->update(['is_approved' => false]);
        return back()->with('status', 'Voter approval revoked successfully');
    }

    // Add the show method here
    public function show(User $voter)
    {
        // Eager load the voter profile with the user
        $voter->load('voterProfile');

        // Verify the voter has a profile
        if (!$voter->voterProfile) {
            abort(404, 'Voter profile not found for this user');
        }

        return view('admin.voters.show', [
            'voter' => $voter,
            'profile' => $voter->voterProfile
        ]);
    }
}
