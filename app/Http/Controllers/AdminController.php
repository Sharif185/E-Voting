<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;          // Add this line
use App\Models\Candidate;    // Add this line
use App\Models\Election;
use App\Models\VoterProfile;// Add this line

class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php


    // app/Http/Controllers/AdminController.php
    public function dashboard()
    {
        // Voter statistics using voter_profiles table
        $totalVoters = User::where('role', 'voter')->count();
        $pendingApprovals = User::where('role', 'voter')
                              ->whereHas('voterProfile', function($query) {
                                  $query->where('is_approved', false);
                              })->count();

        $approvedVoters = User::where('role', 'voter')
                            ->whereHas('voterProfile', function($query) {
                                $query->where('is_approved', true);
                            })->count();

        // Candidate statistics
        $totalCandidates = Candidate::count();
        $activeCandidates = Candidate::where('status', 'active')->count();

        return view('admin.dashboard', compact(
            'totalVoters',
            'pendingApprovals',
            'totalCandidates',
            'approvedVoters',
            'activeCandidates'
        ));
    }
    // app/Http/Controllers/AdminController.php
    public function getPendingApprovalsCount()
    {
        return User::where('role', 'voter')
                  ->whereHas('voterProfile', function($query) {
                      $query->where('is_approved', false);
                  })->count();
    }
}
