<?php

// app/Http/Controllers/Admin/CandidateController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{


       // In your CandidateController's index method
       public function index()
       {
           $pendingApprovals = User::where('role', 'voter')
                             ->whereHas('voterProfile', function($query) {
                                 $query->where('is_approved', false);
                             })->count();

           $candidates = Candidate::paginate(10); // Add pagination

           return view('admin.candidates.index', [
               'candidates' => $candidates,
               'pendingApprovals' => $pendingApprovals
           ]);
       }


    public function create()
    {
        return view('admin.candidates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'gender' => 'required|in:Male,Female,Other',
            'nationality' => 'required|string|max:255',
            'national_id' => 'required|string|unique:candidates',
            'election_id' => 'required|string',
            'election_type' => 'required|in:Mayor,Councilor,MP',
            'constituency' => 'required|in:Mukono,Kampala,Wakiso,Masaka,General',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'party' => 'required|string|max:255',
            'biography' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'manifesto' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'status' => 'required|in:Pending,Active,Withdrawn'
        ]);

        // Handle file uploads
        $photoPath = $request->file('photo')->store('candidate_photos', 'public');
        $biographyPath = $request->file('biography')->store('candidate_biographies', 'public');
        $manifestoPath = $request->file('manifesto')->store('candidate_manifestos', 'public');

        Candidate::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'nationality' => $validated['nationality'],
            'national_id' => $validated['national_id'],
            'election_id' => $validated['election_id'],
            'election_type' => $validated['election_type'],
            'constituency' => $validated['constituency'],
            'photo_path' => $photoPath,
            'party' => $validated['party'],
            'biography_path' => $biographyPath,
            'manifesto_path' => $manifestoPath,
            'status' => $validated['status'],
            'approved' => false
        ]);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate registered successfully!');
    }

    public function approve(Candidate $candidate)
    {
        $candidate->update(['approved' => true]);
        return back()->with('success', 'Candidate approved successfully!');
    }

    public function show(Candidate $candidate)
    {
        $pendingApprovals = User::where('role', 'voter')
                          ->whereHas('voterProfile', function($query) {
                              $query->where('is_approved', false);
                          })->count();

        return view('admin.candidates.show', [
            'candidate' => $candidate,
            'pendingApprovals' => $pendingApprovals
       ]);

    }

    public function edit(Candidate $candidate)
    {
        $pendingApprovals = User::where('role', 'voter')
                          ->whereHas('voterProfile', function($query) {
                              $query->where('is_approved', false);
                          })->count();

        return view('admin.candidates.edit', [
            'candidate' => $candidate,
            'pendingApprovals' => $pendingApprovals
        ]);
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'gender' => 'required|in:Male,Female,Other',
            'nationality' => 'required|string|max:255',
            'national_id' => 'required|string|unique:candidates,national_id,'.$candidate->id,
            'election_id' => 'required|string',
            'election_type' => 'required|in:Mayor,Councilor,MP',
            'constituency' => 'required|in:Mukono,Kampala,Wakiso,Masaka,General',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'party' => 'required|string|max:255',
            'biography' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'manifesto' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'status' => 'required|in:Pending,Active,Withdrawn'
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'nationality' => $validated['nationality'],
            'national_id' => $validated['national_id'],
            'election_id' => $validated['election_id'],
            'election_type' => $validated['election_type'],
            'constituency' => $validated['constituency'],
            'party' => $validated['party'],
            'status' => $validated['status']
        ];

        // Handle file uploads if provided
        if ($request->hasFile('photo')) {
            Storage::delete($candidate->photo_path);
            $updateData['photo_path'] = $request->file('photo')->store('candidate_photos', 'public');
        }

        if ($request->hasFile('biography')) {
            Storage::delete($candidate->biography_path);
            $updateData['biography_path'] = $request->file('biography')->store('candidate_biographies', 'public');
        }

        if ($request->hasFile('manifesto')) {
            Storage::delete($candidate->manifesto_path);
            $updateData['manifesto_path'] = $request->file('manifesto')->store('candidate_manifestos', 'public');
        }

        $candidate->update($updateData);

        return redirect()->route('admin.candidates.show', $candidate)
                         ->with('success', 'Candidate updated successfully!');
    }

    public function destroy(Candidate $candidate)
    {
        // Delete associated files
        Storage::delete([
            $candidate->photo_path,
            $candidate->biography_path,
            $candidate->manifesto_path
        ]);

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
                        ->with('success', 'Candidate deleted successfully!');
    }

}
