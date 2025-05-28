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
                        <a class="nav-link text-dark" href="{{ route('admin.voters.approved') }}">
                            <i class="fas fa-users me-2 text-success"></i> Approved Voters
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link active bg-light rounded" href="{{ route('admin.candidates.index') }}">
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
                    <i class="fas fa-user-tie me-2"></i>Manage Candidates
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Register New Candidate
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="candidatesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Photo</th>
                                    <th>Name</th>
                                    <th>Election Type</th>
                                    <th>Constituency</th>
                                    <th>Party</th>
                                    <th>Status</th>
                                    <th>Approval</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidates as $candidate)
                                <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                    <td class="text-center">
                                        <img src="{{ asset('storage/'.$candidate->photo_path) }}"
                                             alt="Candidate Photo"
                                             width="50"
                                             height="50"
                                             class="rounded-circle shadow-sm object-fit-cover"
                                             onerror="this.src='{{ asset('images/default-profile.png') }}'">
                                    </td>
                                    <td class="fw-bold">{{ $candidate->first_name }} {{ $candidate->last_name }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $candidate->election_type }}</span>
                                    </td>
                                    <td>{{ $candidate->constituency }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $candidate->party_color ?? '#6c757d' }}; color: white;">
                                            {{ $candidate->party }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $candidate->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($candidate->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($candidate->approved)
                                            <span class="badge bg-success rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge bg-warning rounded-pill">
                                                <i class="fas fa-clock me-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if(!$candidate->approved)
                                                <form method="POST" action="{{ route('admin.candidates.approve', $candidate) }}" class="approve-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.candidates.show', $candidate) }}"
                                               class="btn btn-sm btn-info"
                                               data-bs-toggle="tooltip"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.candidates.edit', $candidate) }}"
                                               class="btn btn-sm btn-primary"
                                               data-bs-toggle="tooltip"
                                               title="Edit Candidate">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('admin.candidates.destroy', $candidate) }}"
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete Candidate">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Links -->
                @if($candidates instanceof \Illuminate\Pagination\AbstractPaginator && $candidates->hasPages())
                <div class="card-footer bg-white">
                    {{ $candidates->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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

    .table-hover tbody tr {
        transition: all 0.2s;
    }

    .table-hover tbody tr:hover {
        transform: translateX(2px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .badge.rounded-pill {
        padding: 0.35em 0.65em;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    /* Custom checkbox */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-top: 0.15em;
    }

    /* Action buttons */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }

    /* Custom scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #d1d3e2;
        border-radius: 10px;
    }

    /* Confirmation dialog styling */
    .swal2-popup {
        font-family: 'Nunito', sans-serif;
    }
</style>
@endpush

@push('scripts')
<!-- SweetAlert for confirmation dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables for enhanced table functionality -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Initialize tooltips
    $(document).ready(function() {
        // Initialize Bootstrap tooltips
        $('[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

        // Initialize DataTable
        $('#candidatesTable').DataTable({
            responsive: true,
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search candidates...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            }
        });

        // Confirm before approving
        $('.approve-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Approve Candidate?',
                text: "This candidate will be approved and visible to voters.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading animation
                    Swal.fire({
                        title: 'Approving...',
                        html: 'Please wait while we process your request.',
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit the form
                    this.submit();
                }
            });
        });

        // Confirm before deleting
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete Candidate?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading animation
                    Swal.fire({
                        title: 'Deleting...',
                        html: 'Please wait while we process your request.',
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit the form
                    this.submit();
                }
            });
        });

        // Success message animation
        @if(session('success'))
            setTimeout(() => {
                $('.alert').addClass('animate__fadeOut');
                setTimeout(() => {
                    $('.alert').remove();
                }, 1000);
            }, 3000);
        @endif
    });
</script>
@endpush
