<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::orderBy('start_date', 'desc')->get();
        return view('admin.elections.index', compact('elections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Mayor,Councilor,MP',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'voting_duration_hours' => 'required|integer|min:1',
            'constituency' => 'required|in:Mukono,Kampala,Wakiso,Masaka,General',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Create election with explicit model instantiation
        $election = new Election([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'], // Will be converted to UTC automatically
            'end_date' => $validated['end_date'], // Will be converted to UTC automatically
            'voting_duration_hours' => $validated['voting_duration_hours'],
            'constituency' => $validated['constituency'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? false
        ]);

        $election->save();

        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election created successfully!');
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Mayor,Councilor,MP',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'voting_duration_hours' => 'required|integer|min:1',
            'constituency' => 'required|in:Mukono,Kampala,Wakiso,Masaka,General',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Update using explicit attribute setting
        $election->title = $validated['title'];
        $election->type = $validated['type'];
        $election->start_date = $validated['start_date']; // Will be converted to UTC automatically
        $election->end_date = $validated['end_date']; // Will be converted to UTC automatically
        $election->voting_duration_hours = $validated['voting_duration_hours'];
        $election->constituency = $validated['constituency'];
        $election->description = $validated['description'] ?? null;
        $election->is_active = $validated['is_active'] ?? false;

        $election->save();

        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election updated successfully!');
    }

    public function destroy(Election $election)
    {
        $election->delete();
        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election deleted successfully!');
    }
}
