@extends('layouts.app')

@section('content')
@php
    // Set default values for sidebar
    $pendingApprovals = $pendingApprovals ?? count($pendingVoters ?? []);
@endphp

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background: #f8f9fa; border-right: 1px solid #e9ecef;">
            <div class="position-sticky pt-3">
                <div class="sidebar-header px-3 pb-3 mb-3 border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-university me-2"></i> Voting System
                    </h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link active bg-light rounded" href="{{ route('admin.voters.approvals') }}" style="color: #4e73df;">
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
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-cog me-2 text-secondary"></i> Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Pending Voter Approvals</h2>
                        <span class="badge bg-danger">
                            {{ $pendingApprovals }} Pending
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($pendingVoters->isEmpty())
                        <div class="alert alert-info">
                            No pending voter approvals at this time.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>National ID</th>
                                        <th>Constituency</th>
                                        <th>Election Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingVoters as $voter)
                                    <tr>
                                        <td>{{ $voter->firstname }} {{ $voter->lastname }}</td>
                                        <td>{{ $voter->national_id }}</td>
                                        <td>{{ $voter->constituency }}</td>
                                        <td>{{ $voter->election_type }}</td>
                                        <td>
                                            <form action="{{ route('admin.voters.approve', $voter) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check-circle me-1"></i> Approve
                                                </button>
                                            </form>
                                            <button class="btn btn-info btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#viewVoterModal-{{ $voter->id }}">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- View Voter Modal -->
                                    <div class="modal fade" id="viewVoterModal-{{ $voter->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Voter Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Full Name:</label>
                                                        <p>{{ $voter->firstname }} {{ $voter->lastname }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">National ID:</label>
                                                        <p>{{ $voter->national_id }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Constituency:</label>
                                                        <p>{{ $voter->constituency }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Election Type:</label>
                                                        <p>{{ $voter->election_type }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Registration Date:</label>
                                                        <p>{{ $voter->created_at->format('Y-m-d H:i') }}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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

    /* Main content adjustments */
    main {
        background-color: #f5f7fb;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush
