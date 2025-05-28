<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoterProfile;

class VoterProfileController extends Controller
{
    /**
     * Constructor - Apply middleware
     */
    public function __construct()
    {
        $this->middleware(['auth', 'voter']);
    }

    /**
     * Show the voter registration form
     */
    public function create()
    {
        // Check if user already has a profile
        if (auth()->user()->hasCompletedProfile()) {
            return redirect()->route('voter.dashboard')
                ->with('info', 'You have already completed your voter registration');
        }

        return view('voter.profile-create', [
            'constituencies' => ['Mukono', 'Kampala', 'Masaka', 'Wakiso', 'General'],
            'electionTypes' => ['Mayor', 'Councillor', 'MP']
        ]);
    }

    /**
     * Store the voter registration data
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:120',
            'gender' => 'required|in:male,female,other',
            'national_id' => 'required|string|unique:voter_profiles,national_id|size:14',
            'constituency' => 'required|in:Mukono,Kampala,Masaka,Wakiso,General',
            'election_type' => 'required|in:Mayor,Councillor,MP'
        ]);

        try {
            // Create voter profile
            auth()->user()->voterProfile()->create($validated);

            return redirect()->route('voter.dashboard')
                ->with('success', 'Profile submitted successfully! Please wait for administrator approval.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error submitting profile: ' . $e->getMessage());
        }
    }
}
