<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VoterProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\VoterApprovalController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\VoterElectionsController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ResultsController;


// Show welcome page as default
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Auth::routes(['register' => true]);

// Registration override (if you customized RegisterController)
Route::post('/register', [RegisterController::class, 'register'])
     ->name('register');

// Home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
         ->name('admin.dashboard');

    // Voter approval routes
    Route::prefix('voters')->group(function () {
        Route::get('/approvals', [VoterApprovalController::class, 'index'])
            ->name('admin.voters.approvals');

        Route::post('/approve/{voterProfile}', [VoterApprovalController::class, 'approve'])
            ->name('admin.voters.approve');

        Route::get('/approved', [VoterApprovalController::class, 'approved'])
            ->name('admin.voters.approved');

        Route::patch('/revoke/{voterProfile}', [VoterApprovalController::class, 'revoke'])
            ->name('admin.voters.revoke');

        // Added voter detail and management routes
        Route::get('/admin/voters/{user}', [VoterApprovalController::class, 'show'])
            ->name('admin.voters.show');

        Route::patch('/{voter}/approve', [VoterApprovalController::class, 'approveVoter'])
            ->name('admin.voters.approve-voter');

        Route::patch('/{voter}/revoke', [VoterApprovalController::class, 'revokeVoter'])
            ->name('admin.voters.revoke-voter');

        Route::put('/{voter}', [VoterApprovalController::class, 'update'])
            ->name('admin.voters.update');
    });

    // Election routes
    Route::resource('elections', \App\Http\Controllers\Admin\ElectionController::class)
        ->except(['show'])
        ->names('admin.elections');

    // Candidate management routes
    Route::resource('candidates', CandidateController::class)->names([
        'index' => 'admin.candidates.index',
        'create' => 'admin.candidates.create',
        'store' => 'admin.candidates.store',
        'show' => 'admin.candidates.show',
        'edit' => 'admin.candidates.edit',
        'update' => 'admin.candidates.update',
        'destroy' => 'admin.candidates.destroy'
    ]);

    Route::post('/candidates/{candidate}/approve', [CandidateController::class, 'approve'])
        ->name('admin.candidates.approve');

    // Election Results Routes
    Route::get('/results/analytics', [ResultsController::class, 'analytics'])
        ->name('admin.results.analytics');

    Route::post('/results/calculate/{election}', [ResultsController::class, 'calculateResults'])
        ->name('admin.results.calculate');
});

// Voter routes
Route::middleware(['auth', 'voter'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [VoterController::class, 'dashboard'])
         ->name('voter.dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/create', [VoterProfileController::class, 'create'])
            ->name('voter.profile.create');

        Route::post('/store', [VoterProfileController::class, 'store'])
            ->name('voter.profile.store');
    });

    // Election routes - grouped under /elections prefix
    Route::prefix('elections')->group(function () {
        Route::get('/pending', [VoterElectionsController::class, 'showPendingElections'])
            ->name('voter.elections.pending');

        Route::get('/{election}', [VoterElectionsController::class, 'show'])
            ->name('voter.elections.show');

        // Voting routes
        Route::post('/{election}/vote', [VoteController::class, 'submit'])
            ->name('votes.submit');

        Route::get('/vote/confirmation', [VoteController::class, 'confirmation'])
            ->name('votes.confirmation');
    });
});

// Public routes (accessible without authentication)
Route::get('/elections/{election}/results', [ResultsController::class, 'showResults'])
    ->name('elections.results.show');

// Common authenticated routes
Route::middleware(['auth'])->group(function () {
    // You can add common authenticated routes here if needed
});
