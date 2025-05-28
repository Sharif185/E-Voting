@extends('layouts.app')

@section('content')
@php
    // Set default values for sidebar
    $pendingApprovals = $pendingApprovals ?? 0;
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
                        <a class="nav-link active bg-light rounded" href="{{ route('admin.elections.index') }}" style="color: #4e73df;">
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

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Manage Elections</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createElectionModal">
                        <i class="fas fa-plus"></i> Create New Election
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Constituency</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($elections as $election)
                                <tr>
                                    <td>{{ $election->title }}</td>
                                    <td>{{ $election->type }}</td>
                                    <td>{{ $election->constituency }}</td>
                                    <td>{{ $election->start_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $election->end_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $election->voting_duration_hours }} hours</td>
                                    <td>
                                        @if($election->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @elseif(now() > $election->end_date)
                                            <span class="badge bg-secondary">Completed</span>
                                        @elseif(now() >= $election->start_date && now() <= $election->end_date)
                                            <span class="badge bg-primary">In Progress</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.elections.edit', $election->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.elections.destroy', $election->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Create Election Modal -->
<div class="modal fade" id="createElectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Election</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createElectionForm" action="{{ route('admin.elections.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Election Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Election Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Mayor">Mayor</option>
                                <option value="Councilor">Councilor</option>
                                <option value="MP">MP</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="voting_duration_hours" class="form-label">Voting Duration (hours)</label>
                            <input type="number" class="form-control" id="voting_duration_hours"
                                   name="voting_duration_hours" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="constituency" class="form-label">Constituency</label>
                            <select class="form-select" id="constituency" name="constituency" required>
                                <option value="">Select Constituency</option>
                                <option value="Mukono">Mukono</option>
                                <option value="Kampala">Kampala</option>
                                <option value="Wakiso">Wakiso</option>
                                <option value="Masaka">Masaka</option>
                                <option value="General">General (All Constituencies)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1">
                        <label class="form-check-label" for="is_active">Activate this election immediately</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Election</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Election Modal -->
<div class="modal fade" id="editElectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Election</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editElectionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading election details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Election</button>
                </div>
            </form>
        </div>
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
</style>
@endpush

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle edit button clicks
        $('.edit-election').click(function() {
            const electionId = $(this).data('id');
            const editModal = $('#editElectionModal');
            const modalBody = $('#editModalBody');

            // Show modal immediately
            editModal.modal('show');

            // Clear previous content and show loading
            modalBody.html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading election details...</p>
                </div>
            `);

            // Fetch election data via AJAX
            $.get(`/admin/elections/${electionId}/edit`, function(data) {
                modalBody.html(data);
            }).fail(function() {
                modalBody.html(`
                    <div class="alert alert-danger">
                        Failed to load election details. Please try again.
                    </div>
                `);
            });
        });

        // Validate end date is after start date for both forms
        $('#createElectionForm, #editElectionForm').on('submit', function(e) {
            const startDate = new Date($(this).find('input[name="start_date"]').val());
            const endDate = new Date($(this).find('input[name="end_date"]').val());

            if (endDate <= startDate) {
                alert('End date must be after start date');
                e.preventDefault();
            }
        });
    });
</script>
@endsection
