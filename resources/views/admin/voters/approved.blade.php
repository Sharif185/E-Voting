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
                    <i class="fas fa-users me-2"></i> Approved Voters
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.voters.approvals') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-clock me-2"></i> Pending Approvals
                    </a>
                </div>
            </div>

            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="votersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>National ID</th>
                                    <th>Constituency</th>
                                    <th>Election Type</th>
                                    <th>Approved On</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedVoters as $voter)
                                <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                    <td class="fw-bold">{{ $voter->id }}</td>
                                    <td>{{ $voter->firstname }} {{ $voter->lastname }}</td>
                                    <td>{{ $voter->national_id }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $voter->constituency }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $voter->election_type }}</span>
                                    </td>
                                    <td>{{ $voter->updated_at->format('M d, Y H:i') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="#"
                                               class="btn btn-sm btn-info"
                                               data-bs-toggle="tooltip"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.voters.revoke', $voter) }}"
                                                  method="POST"
                                                  class="revoke-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-warning"
                                                        data-bs-toggle="tooltip"
                                                        title="Revoke Approval">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No approved voters found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($approvedVoters instanceof \Illuminate\Pagination\AbstractPaginator && $approvedVoters->hasPages())
                <div class="card-footer bg-white">
                    {{ $approvedVoters->links() }}
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

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
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
        $('#votersTable').DataTable({
            responsive: true,
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search voters...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            }
        });

        // Confirm before revoking approval
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
                    // Show loading animation
                    Swal.fire({
                        title: 'Revoking...',
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
        @if(session('status'))
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
