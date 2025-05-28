@extends('layouts.app')

@section('content')
@php
    // Get pending approvals count for sidebar badge
    $pendingApprovals = App\Models\User::where('role', 'voter')
                          ->whereHas('voterProfile', function($query) {
                              $query->where('is_approved', false);
                          })->count();
@endphp

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
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
                            <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard
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
                        <a class="nav-link active bg-light rounded" href="{{ route('admin.voters.approved') }}">
                            <i class="fas fa-users me-2 text-success"></i> Approved Voters
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('admin.candidates.index') }}">
                            <i class="fas fa-user-tie me-2 text-info"></i> Candidates
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="background: #f5f7fb;">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h3 fw-bold" style="color: #4e73df;">
                    <i class="fas fa-user me-2"></i> Voter Details
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.voters.approved') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
            </div>

            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4 animate__animated animate__fadeIn">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Voter Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%" class="bg-light">ID</th>
                                            <td>{{ $voter->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Name</th>
                                            <td>{{ $voter->firstname }} {{ $voter->lastname }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">National ID</th>
                                            <td>{{ $voter->national_id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Constituency</th>
                                            <td>
                                                <span class="badge bg-primary">{{ $voter->constituency }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Election Type</th>
                                            <td>
                                                <span class="badge bg-info">{{ $voter->election_type }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Approved On</th>
                                            <td>
                                                @if($voter->voterProfile && $voter->voterProfile->is_approved)
                                                    {{ $voter->voterProfile->updated_at->format('M d, Y H:i') }}
                                                @else
                                                    <span class="text-muted">Not approved</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4 animate__animated animate__fadeIn">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Additional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email Address</label>
                                <p class="form-control-static fw-bold">{{ $voter->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Phone Number</label>
                                <p class="form-control-static fw-bold">{{ $voter->phone }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Registration Date</label>
                                <p class="form-control-static fw-bold">{{ $voter->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm animate__animated animate__fadeIn">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($voter->voterProfile && $voter->voterProfile->is_approved)
                                    <form action="{{ route('admin.voters.revoke', $voter) }}" method="POST" class="revoke-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="fas fa-user-times me-2"></i> Revoke Approval
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.voters.approve', $voter) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-user-check me-2"></i> Approve Voter
                                        </button>
                                    </form>
                                @endif

                                <a href="mailto:{{ $voter->email }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-envelope me-2"></i> Send Email
                                </a>

                                <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fas fa-edit me-2"></i> Edit Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Voter Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.voters.update', $voter) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $voter->firstname }}">
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $voter->lastname }}">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $voter->phone }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    .form-control-static {
        padding: 0.375rem 0;
        margin-bottom: 0;
        min-height: 1.5rem;
    }

    .card {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }

    .badge {
        font-size: 0.85em;
        font-weight: 500;
    }

    table.table-bordered th {
        background-color: #f8f9fa !important;
    }

    table.table-bordered td, table.table-bordered th {
        border: 1px solid #dee2e6;
        padding: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tooltips
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Revoke confirmation
        $('.revoke-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Revoke Approval?',
                text: "This voter will need to be approved again to participate.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, revoke it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush

@endsection
