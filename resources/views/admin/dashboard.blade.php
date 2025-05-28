@extends('layouts.app')

@section('content')
@php
    // Set default values
    $pendingApprovals = $pendingApprovals ?? 0;
    $totalVoters = $totalVoters ?? 0;
    $totalCandidates = $totalCandidates ?? 0;
    $approvedVoters = $approvedVoters ?? 0;
    $activeCandidates = $activeCandidates ?? 0;
@endphp

<div class="container-fluid">
    <div class="row">
        <!-- Professional Light Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background: #f8f9fa; border-right: 1px solid #e9ecef;">
            <div class="position-sticky pt-3">
                <div class="sidebar-header px-3 pb-3 mb-3 border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-university me-2"></i> Voting System
                    </h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link active bg-light rounded" href="#" style="color: #4e73df;">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.voters.approvals') }}">
                            <i class="fas fa-user-check me-2 text-warning"></i> Voter Approvals
                            @if($pendingApprovals > 0)
                            <span class="badge bg-danger ms-2">{{ $pendingApprovals }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.voters.approved') }}">
                            <i class="fas fa-users me-2 text-success"></i> Approved Voters
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.candidates.index') }}">
                            <i class="fas fa-user-tie me-2 text-info"></i> Candidates
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.elections.index') }}">
                            <i class="fas fa-vote-yea me-2 text-primary"></i> Manage Elections
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.results.analytics') }}">
                            <i class="fas fa-chart-pie me-2 text-primary"></i> Election Analytics
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="background: #f5f7fb;">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h3 fw-bold" style="color: #4e73df;">Dashboard Overview</h1>
                <div class="digital-clock bg-primary text-white p-2 rounded">
                    <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                    <span id="ampm" class="ms-1">AM</span>
                </div>
                <a href="{{ route('admin.results.analytics') }}" class="btn btn-primary ms-3">
                    <i class="fas fa-chart-bar me-1"></i> View Analytics
                </a>
            </div>

            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Vibrant Stats Cards -->
            <div class="row mb-4">
                <!-- Total Voters -->
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card stat-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-white-50 mb-2">Total Voters</h6>
                                    <h2 class="mb-0">{{ $totalVoters }}</h2>
                                </div>
                                <div class="icon-shape rounded-circle bg-white-20 p-2">
                                    <i class="fas fa-users fa-lg text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress bg-white-30" style="height: 6px;">
                                    <div class="progress-bar bg-white" role="progressbar"
                                         style="width: {{ min(100, ($totalVoters/1000)*100) }}%"></div>
                                </div>
                                <small class="text-white-50 mt-1 d-block">+{{ rand(5, 15) }}% this week</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card stat-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-white-50 mb-2">Pending</h6>
                                    <h2 class="mb-0">{{ $pendingApprovals }}</h2>
                                </div>
                                <div class="icon-shape rounded-circle bg-white-20 p-2">
                                    <i class="fas fa-user-clock fa-lg text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress bg-white-30" style="height: 6px;">
                                    <div class="progress-bar bg-white" role="progressbar"
                                         style="width: {{ min(100, ($pendingApprovals/50)*100) }}%"></div>
                                </div>
                                <small class="text-white-50 mt-1 d-block">Requires attention</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approved Voters -->
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card stat-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-white-50 mb-2">Approved</h6>
                                    <h2 class="mb-0">{{ $approvedVoters }}</h2>
                                </div>
                                <div class="icon-shape rounded-circle bg-white-20 p-2">
                                    <i class="fas fa-user-check fa-lg text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress bg-white-30" style="height: 6px;">
                                    <div class="progress-bar bg-white" role="progressbar"
                                         style="width: {{ min(100, ($approvedVoters/1000)*100) }}%"></div>
                                </div>
                                <small class="text-white-50 mt-1 d-block">+{{ rand(2, 8) }}% today</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Candidates -->
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card stat-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-white-50 mb-2">Candidates</h6>
                                    <h2 class="mb-0">{{ $totalCandidates }}</h2>
                                </div>
                                <div class="icon-shape rounded-circle bg-white-20 p-2">
                                    <i class="fas fa-user-tie fa-lg text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress bg-white-30" style="height: 6px;">
                                    <div class="progress-bar bg-white" role="progressbar"
                                         style="width: {{ min(100, ($totalCandidates/50)*100) }}%"></div>
                                </div>
                                <small class="text-white-50 mt-1 d-block">Active: {{ $activeCandidates }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Row -->
            <div class="row">
                <!-- Recent Activity -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold" style="color: #4e73df;">
                                <i class="fas fa-history me-2"></i>Recent Activity
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="activity-feed">
                                <div class="feed-item p-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="avatar bg-primary-light text-primary me-3">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div>
                                            <p class="mb-1 fw-bold">{{ $pendingApprovals }} new registrations pending review</p>
                                            <small class="text-muted">Just now</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="feed-item p-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="avatar bg-success-light text-success me-3">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div>
                                            <p class="mb-1 fw-bold">{{ rand(1, 5) }} voters approved today</p>
                                            <small class="text-muted">15 minutes ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="feed-item p-3">
                                    <div class="d-flex">
                                        <div class="avatar bg-info-light text-info me-3">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="mb-1 fw-bold">New candidate registered</p>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold" style="color: #f6c23e;">
                                <i class="fas fa-bolt me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.voters.approvals') }}" class="btn btn-primary w-100 text-start py-2">
                                        <i class="fas fa-user-check me-2"></i> Review Approvals
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-success w-100 text-start py-2">
                                        <i class="fas fa-user-tie me-2"></i> Manage Candidates
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('admin.elections.index') }}" class="btn btn-info w-100 text-start py-2">
                                        <i class="fas fa-vote-yea me-2"></i> Manage Elections
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('admin.results.analytics') }}" class="btn btn-warning w-100 text-start py-2">
                                        <i class="fas fa-chart-pie me-2"></i> View Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<style>
    .sidebar {
        min-height: calc(100vh - 56px);
        background: #f8f9fa;
        box-shadow: 1px 0 5px rgba(0,0,0,0.05);
    }

    .sidebar .nav-link {
        transition: all 0.3s;
        padding: 0.5rem 1rem;
        border-radius: 0.35rem;
    }

    .sidebar .nav-link:hover {
        background-color: #e9ecef;
        color: #4e73df !important;
    }

    .sidebar .nav-link.active {
        background-color: #e9ecef;
        color: #4e73df !important;
        font-weight: 600;
    }

    .sidebar-header {
        padding: 1rem 1rem 0.5rem;
    }

    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .icon-shape {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .bg-white-20 {
        background-color: rgba(255,255,255,0.2);
    }

    .bg-white-30 {
        background-color: rgba(255,255,255,0.3);
    }

    .activity-feed {
        max-height: 300px;
        overflow-y: auto;
    }

    .feed-item:hover {
        background-color: #f8f9fa;
    }

    .avatar {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .digital-clock {
        font-family: 'Courier New', monospace;
        font-size: 1rem;
        font-weight: bold;
        background: #4e73df;
    }

    /* Pulse animation for pending approvals */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(246, 194, 62, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(246, 194, 62, 0); }
        100% { box-shadow: 0 0 0 0 rgba(246, 194, 62, 0); }
    }

    .pulse-alert {
        animation: pulse 2s infinite;
    }

    /* Custom scrollbar */
    .activity-feed::-webkit-scrollbar {
        width: 5px;
    }

    .activity-feed::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .activity-feed::-webkit-scrollbar-thumb {
        background: #d1d3e2;
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Digital clock
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');

        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes;
        document.getElementById('seconds').textContent = seconds;
        document.getElementById('ampm').textContent = ampm;
    }

    setInterval(updateClock, 1000);
    updateClock();

    // Add pulse animation if there are pending approvals
    document.addEventListener('DOMContentLoaded', function() {
        const pendingApprovals = {{ $pendingApprovals }};
        if (pendingApprovals > 0) {
            const pendingCard = document.querySelector('.stat-card:nth-child(2)');
            if (pendingCard) {
                pendingCard.classList.add('pulse-alert');
            }
        }
    });
</script>
@endpush
