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
                        <a class="nav-link text-dark" href="{{ route('admin.elections.index') }}">
                            <i class="fas fa-vote-yea me-2 text-primary"></i> Manage Elections
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link active bg-light rounded" href="{{ route('admin.results.analytics') }}" style="color: #4e73df;">
                            <i class="fas fa-chart-pie me-2 text-primary"></i> Election Analytics
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Enhanced Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4" style="background-color: #f8fafc;">
            <!-- Page Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
                <div>
                    <h1 class="h2 mb-0 text-gray-800">
                        <i class="fas fa-chart-line text-primary me-2"></i>Election Analytics Dashboard
                    </h1>
                    <p class="mb-0 text-muted">Comprehensive overview of election statistics and results</p>
                </div>
            </div>

            <!-- Status Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Colorful Summary Cards -->
            <div class="row mb-4">
                <!-- Top Constituency by Votes -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                        Top Constituency</div>
                                    <div class="h5 mb-0 font-weight-bold text-white">
                                        {{ $constituencyStats->first()->constituency ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-map-marked-alt fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 py-2">
                            <small class="text-white-50">
                                {{ number_format($constituencyStats->first()->total_votes ?? 0) }} votes
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Total Votes Across All Constituencies -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                        Total Votes</div>
                                    <div class="h2 mb-0 font-weight-bold text-white">
                                        {{ number_format($constituencyStats->sum('total_votes')) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-vote-yea fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 py-2">
                            <small class="text-white-50">Across all constituencies</small>
                        </div>
                    </div>
                </div>

                <!-- Unique Voters -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                        Unique Voters</div>
                                    <div class="h2 mb-0 font-weight-bold text-white">
                                        {{ number_format($constituencyStats->sum('unique_voters')) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 py-2">
                            <small class="text-white-50">Distinct voters participated</small>
                        </div>
                    </div>
                </div>

                <!-- Average Turnout -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                        Avg. Turnout</div>
                                    <div class="h2 mb-0 font-weight-bold text-white">
                                        @php
                                            $totalVotes = $constituencyStats->sum('total_votes');
                                            $totalVoters = $constituencyStats->sum('unique_voters');
                                            $avgTurnout = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100) : 0;
                                        @endphp
                                        {{ $avgTurnout }}%
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percentage fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 py-2">
                            <small class="text-white-50">Average voter participation</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="row">
                <!-- Constituency Statistics -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-map-marked-alt me-1"></i> Constituency Voting Statistics
                            </h6>
                            <button class="btn btn-sm btn-outline-primary" id="printConstituencyBtn">
                                <i class="fas fa-print me-1"></i> Print Results
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0" id="constituencyTable">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th>Constituency</th>
                                            <th class="text-end">Total Votes</th>
                                            <th class="text-end">Unique Voters</th>
                                            <th class="text-end">Turnout %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($constituencyStats as $stat)
                                        <tr>
                                            <td>{{ $stat->constituency }}</td>
                                            <td class="text-end">{{ number_format($stat->total_votes) }}</td>
                                            <td class="text-end">{{ number_format($stat->unique_voters) }}</td>
                                            <td class="text-end">
                                                @php
                                                    $turnout = $stat->unique_voters > 0
                                                        ? round(($stat->total_votes / $stat->unique_voters) * 100)
                                                        : 0;
                                                @endphp
                                                {{ $turnout }}%
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Election Results -->
                <div class="col-lg-6 mb-4">
                    @foreach($elections as $election)
                    <div class="card shadow">
                        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-poll me-1"></i> {{ $election->title }} ({{ $election->type }})
                            </h6>
                            <div>
                                @if($election->results->count() > 0)
                                    <form action="{{ route('admin.results.calculate', $election) }}" method="POST" class="d-inline me-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to recalculate results? This will update all rankings based on current votes.')">
                                            <i class="fas fa-sync-alt me-1"></i> Recalculate
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-sm btn-outline-primary print-election-btn" data-election-id="{{ $election->id }}">
                                    <i class="fas fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($election->results->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover election-table" width="100%" cellspacing="0" data-election-id="{{ $election->id }}">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th>Position</th>
                                                <th>Candidate</th>
                                                <th class="text-end">Votes</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($election->results as $result)
                                            <tr>
                                                <td>{{ $result->position }}</td>
                                                <td>{{ $result->candidate->first_name }} {{ $result->candidate->last_name }}</td>
                                                <td class="text-end">{{ number_format($result->votes_count) }}</td>
                                                <td>
                                                    @if($result->is_winner)
                                                        <span class="badge bg-success"><i class="fas fa-trophy me-1"></i> Winner</span>
                                                    @else
                                                        <span class="badge bg-secondary">Runner-up</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <span>No results calculated yet for this election.</span>
                                        </div>
                                        <form action="{{ route('admin.results.calculate', $election) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-calculator me-1"></i> Calculate Results
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<style>
    /* Sidebar styles */
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

    /* Main content enhancements */
    main {
        background-color: #f8fafc !important;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .table {
        font-size: 0.9rem;
    }

    .table thead th {
        border-bottom-width: 1px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    .bg-gray-100 {
        background-color: #f8f9fa;
    }

    /* Summary card enhancements */
    .text-white-50 {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* Animation for cards */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .col-xl-3 .card {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Different delays for each card */
    .col-xl-3:nth-child(1) .card {
        animation-delay: 0.1s;
    }
    .col-xl-3:nth-child(2) .card {
        animation-delay: 0.2s;
    }
    .col-xl-3:nth-child(3) .card {
        animation-delay: 0.3s;
    }
    .col-xl-3:nth-child(4) .card {
        animation-delay: 0.4s;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Print constituency results function
        document.getElementById('printConstituencyBtn').addEventListener('click', function() {
            const table = document.getElementById('constituencyTable');
            const tableClone = table.cloneNode(true);

            // Create print styles
            const printStyles = `
                <style>
                    @media print {
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2c3e50; text-align: center; margin-bottom: 5px; }
                        .report-date { color: #7f8c8d; text-align: center; font-size: 14px; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa !important; }
                        .text-end { text-align: right !important; }
                        .print-footer { text-align: center; margin-top: 30px; font-size: 12px; color: #95a5a6; }
                        @page { size: auto; margin: 10mm; }
                    }
                </style>
            `;

            // Create print content
            const printContent = `
                <html>
                    <head>
                        <title>Constituency Election Results</title>
                        ${printStyles}
                    </head>
                    <body onload="window.print();window.close()">
                        <h1>Constituency Election Results</h1>
                        <div class="report-date">Generated on: ${new Date().toLocaleString()}</div>
                        ${tableClone.outerHTML}
                        <div class="print-footer">
                            <hr style="border-top: 1px solid #eee; margin: 10px 0;">
                            <p>Official Election Results - {{ config('app.name') }}</p>
                        </div>
                    </body>
                </html>
            `;

            // Open print window
            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();
        });

        // Print election results function
        document.querySelectorAll('.print-election-btn').forEach(button => {
            button.addEventListener('click', function() {
                const electionId = this.getAttribute('data-election-id');
                const table = document.querySelector(`.election-table[data-election-id="${electionId}"]`);
                const tableClone = table.cloneNode(true);
                const electionTitle = this.closest('.card').querySelector('.card-header h6').textContent.trim();

                // Create print styles
                const printStyles = `
                    <style>
                        @media print {
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            h1 { color: #2c3e50; text-align: center; margin-bottom: 5px; }
                            .report-date { color: #7f8c8d; text-align: center; font-size: 14px; margin-bottom: 20px; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f8f9fa !important; }
                            .text-end { text-align: right !important; }
                            .badge { padding: 0.25em 0.4em; font-size: 75%; font-weight: 700; }
                            .badge-success { background-color: #28a745 !important; }
                            .badge-secondary { background-color: #6c757d !important; }
                            .print-footer { text-align: center; margin-top: 30px; font-size: 12px; color: #95a5a6; }
                            @page { size: auto; margin: 10mm; }
                        }
                    </style>
                `;

                // Create print content
                const printContent = `
                    <html>
                        <head>
                            <title>${electionTitle} - Results</title>
                            ${printStyles}
                        </head>
                        <body onload="window.print();window.close()">
                            <h1>${electionTitle} - Results</h1>
                            <div class="report-date">Generated on: ${new Date().toLocaleString()}</div>
                            ${tableClone.outerHTML}
                            <div class="print-footer">
                                <hr style="border-top: 1px solid #eee; margin: 10px 0;">
                                <p>Official Election Results - {{ config('app.name') }}</p>
                            </div>
                        </body>
                    </html>
                `;

                // Open print window
                const printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write(printContent);
                printWindow.document.close();
            });
        });
    });
</script>
@endpush
@endsection
