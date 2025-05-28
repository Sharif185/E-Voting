<?php

// app/Http/Controllers/VoterController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->hasCompletedProfile()) {
            return redirect()->route('voter.profile.create');
        }

        if (!$user->isApprovedVoter()) {
            return view('voter.pending-approval');
        }

        return view('voter.dashboard', [
            'profile' => $user->voterProfile
        ]);
    }
}
