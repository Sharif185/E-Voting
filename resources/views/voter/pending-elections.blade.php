@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Debugging output --}}
    @php
        $debugInfo = [];
        foreach($elections as $election) {
            $debugInfo[] = [
                'title' => $election->title,
                'start' => $election->start_date,
                'end' => $election->end_date,
                'isActive' => $election->isActive(),
                'isFuture' => $election->isFuture(),
                'now' => now()
            ];
        }
    @endphp
    <div style="display:none" id="debugElections">{{ json_encode($debugInfo) }}</div>

    {{-- Error message display section --}}
    @if($errors->any() || session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $error)
                <strong>Error!</strong> {{ $error }}<br>
            @endforeach
            @if(session('error'))
                <strong>Error!</strong> {{ session('error') }}
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($elections->isNotEmpty())
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="fas fa-vote-yea me-2"></i>Upcoming Elections</h2>
        <div class="badge bg-primary rounded-pill">{{ $elections->count() }} Elections</div>
    </div>
    @endif

    @forelse($elections as $election)
    @php
        $isElectionActive = $election->isActive();
        $isElectionFuture = $election->isFuture();
        $index = $loop->index;

        // Debug current election
        $currentDebug = [
            'title' => $election->title,
            'isActive' => $isElectionActive,
            'isFuture' => $isElectionFuture,
            'start' => $election->start_date->format('Y-m-d H:i:s'),
            'end' => $election->end_date->format('Y-m-d H:i:s'),
            'now' => now()->format('Y-m-d H:i:s')
        ];
    @endphp
    <div class="card shadow-sm mb-4 border-0" data-election-index="{{ $index }}" data-debug="{{ json_encode($currentDebug) }}">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ $election->title }} <small class="text-light">({{ $election->type }})</small></h3>
                <span class="badge bg-light text-primary">{{ $election->constituency }}</span>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-{{ $isElectionActive ? 'success' : ($isElectionFuture ? 'secondary' : 'warning') }} mb-4">
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="mb-2">
                        <strong><i class="fas fa-play-circle me-2"></i>Starts:</strong>
                        <span id="startTimeDisplay-{{ $index }}" data-raw-start="{{ $election->start_date->toIso8601String() }}">
                            {{ $election->start_date->format('M d, Y H:i') }} (EAT)
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong><i class="fas fa-stop-circle me-2"></i>Ends:</strong>
                        <span id="endTimeDisplay-{{ $index }}" data-raw-end="{{ $election->end_date->toIso8601String() }}">
                            {{ $election->end_date->format('M d, Y H:i') }} (EAT)
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong><i class="fas fa-clock me-2"></i>Duration:</strong>
                        <span class="badge bg-primary rounded-pill">{{ $election->voting_duration_hours }} hours</span>
                    </div>
                    <div class="mb-2">
                        <strong><i class="fas fa-hourglass-half me-2"></i>Remaining Time:</strong>
                        <span id="remainingTime-{{ $index }}" class="fw-bold">Loading...</span>
                    </div>
                    <div class="mb-2">
                        <strong><i class="fas fa-info-circle me-2"></i>Status:</strong>
                        <span id="electionStatus-{{ $index }}" class="fw-bold">
                            @if($isElectionActive)
                                Voting Active
                            @elseif($isElectionFuture)
                                Voting Not Started
                            @else
                                Voting Ended
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Election Details</h5>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                This election will be held in {{ $election->constituency }} constituency.
                                @if(auth()->user()->voterProfile)
                                <div class="mt-2">
                                    Your voter status:
                                    <span class="badge bg-{{ auth()->user()->voterProfile->is_approved ? 'success' : 'warning' }}">
                                        {{ auth()->user()->voterProfile->is_approved ? 'Approved' : 'Pending Approval' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-alt me-2"></i>Your Local Time</h5>
                            <div class="alert alert-secondary mb-0">
                                <i class="fas fa-clock me-2"></i>
                                <span id="clientTimeDisplay">Loading your local time...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mb-3 border-bottom pb-2"><i class="fas fa-users me-2"></i>Candidates</h4>

            @if($election->candidates->count() > 0)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($election->candidates as $candidate)
                @php
                    $cardClass = 'card h-100 candidate-card';
                    $cardStyle = '';

                    if ($isElectionFuture) {
                        $cardClass .= ' bg-light-red';
                        $cardStyle = 'opacity: 0.8;';
                    } elseif (!$isElectionActive) {
                        $cardClass .= ' bg-light';
                        $cardStyle = 'opacity: 0.8;';
                    }

                    // Debug card classes
                    $cardDebug = [
                        'cardClass' => $cardClass,
                        'cardStyle' => $cardStyle,
                        'isFuture' => $isElectionFuture,
                        'isActive' => $isElectionActive
                    ];
                @endphp
                <div class="col">
                    <div class="{{ $cardClass }}" style="{{ $cardStyle }}" data-debug-card="{{ json_encode($cardDebug) }}">
                        <div class="photo-container d-flex justify-content-center align-items-center p-3" style="height: 300px; background-color: #f8f9fa;">
                            @php
                                $photoPath = $candidate->photo_path
                                    ? 'storage/candidate_photos/' . basename($candidate->photo_path)
                                    : 'images/default-candidate.jpg';

                                $fullPath = $candidate->photo_path
                                    ? public_path('storage/candidate_photos/' . basename($candidate->photo_path))
                                    : public_path('images/default-candidate.jpg');

                                $fileExists = file_exists($fullPath);
                            @endphp

                            @if($fileExists)
                                <img src="{{ asset($photoPath) }}"
                                     class="img-fluid mh-100 mw-100"
                                     style="object-fit: contain; max-height: 280px; max-width: 100%;"
                                     onerror="this.src='{{ asset('images/default-candidate.jpg') }}'">
                            @else
                                <div class="text-center text-danger">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                    <img src="{{ asset('images/default-candidate.jpg') }}"
                                         class="img-fluid"
                                         style="max-height: 200px;">
                                    <p class="small mt-2">Photo not available</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $candidate->first_name }} {{ $candidate->last_name }}</h5>
                            <p class="card-text">
                                <span class="badge bg-secondary">{{ $candidate->party }}</span>
                                <span class="badge bg-info ms-1">{{ $candidate->election_type }}</span>
                            </p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-id-card me-2"></i>ID</span>
                                    <span>{{ $candidate->national_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-flag me-2"></i>Status</span>
                                    <span class="badge bg-{{ strtolower($candidate->status) == 'active' ? 'success' : 'warning' }}">
                                        {{ $candidate->status }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-outline-primary btn-sm view-profile"
                                        data-candidate-id="{{ $candidate->id }}">
                                    <i class="fas fa-user-circle me-1"></i> Profile
                                </button>

                                @if(auth()->user()->voterProfile && auth()->user()->voterProfile->is_approved)
                                    @if(!auth()->user()->voterProfile->hasVotedInElection($election->id))
                                        <form action="{{ route('votes.submit', $election) }}" method="POST" class="vote-form">
                                            @csrf
                                            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                            <button type="submit" class="btn btn-success btn-sm vote-btn" {{ !$isElectionActive ? 'disabled' : '' }}>
                                                <i class="fas fa-vote-yea me-1"></i> Vote
                                            </button>
                                        </form>
                                    @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i> Voted
                                    </span>
                                    @endif
                                @else
                                <span class="text-muted small">
                                    @if(auth()->user()->voterProfile)
                                        Not approved to vote
                                    @else
                                        No voter profile
                                    @endif
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading">No Approved Candidates</h5>
                        <p class="mb-2">{{ $election->type }} election in {{ $election->constituency }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading">No Upcoming Elections</h5>
                <p class="mb-0">There are currently no scheduled elections. Please check back later.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<style>
    /* FORCEFUL STYLING - using !important to ensure application */
    .bg-light-red {
        background-color: #ffebee !important;
        border: 1px solid #ffcdd2 !important;
    }
    .bg-light {
        background-color: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
    }
    .candidate-card {
        transition: all 0.3s ease !important;
    }
    .photo-container {
        position: relative;
        border-bottom: 1px solid #eee;
        min-height: 300px;
    }
    .img-fluid {
        display: block;
        margin: 0 auto;
        transition: opacity 0.3s ease;
    }
    .text-danger small {
        font-size: 0.8rem;
    }
    .vote-btn:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debugging function to check why styles aren't applying
    function checkAppliedStyles() {
        console.log("=== ELECTIONS DEBUG INFO ===");
        const debugData = JSON.parse(document.getElementById('debugElections').textContent);
        console.table(debugData);

        document.querySelectorAll('[data-debug]').forEach(el => {
            const data = JSON.parse(el.dataset.debug);
            console.group(`Election: ${data.title}`);
            console.log('Start:', data.start);
            console.log('End:', data.end);
            console.log('Now:', data.now);
            console.log('isActive:', data.isActive);
            console.log('isFuture:', data.isFuture);
            console.groupEnd();
        });

        document.querySelectorAll('[data-debug-card]').forEach(el => {
            const data = JSON.parse(el.dataset.debugCard);
            console.group('Candidate Card Styles');
            console.log('Applied Class:', data.cardClass);
            console.log('Applied Style:', data.cardStyle);
            console.log('Computed Style:', window.getComputedStyle(el));
            console.groupEnd();
        });
    }

    // Run the debug check
    checkAppliedStyles();

    // Time management functions
    const updateTimeDisplays = () => {
        try {
            const now = new Date();
            document.getElementById('clientTimeDisplay').textContent =
                now.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZoneName: 'short'
                });

            document.querySelectorAll('[data-election-index]').forEach(card => {
                const index = card.dataset.electionIndex;
                const startTime = new Date(card.querySelector('#startTimeDisplay-' + index).dataset.rawStart);
                const endTime = new Date(card.querySelector('#endTimeDisplay-' + index).dataset.rawEnd);

                const formatTime = (target) => {
                    const diff = Math.max(0, (target - now) / 1000);
                    const days = Math.floor(diff / 86400);
                    const hours = Math.floor((diff % 86400) / 3600);
                    const minutes = Math.floor((diff % 3600) / 60);
                    const seconds = Math.floor(diff % 60);
                    return days ? `${days}d ${hours}h ${minutes}m` :
                           hours ? `${hours}h ${minutes}m ${seconds}s` :
                           minutes ? `${minutes}m ${seconds}s` : `${seconds}s`;
                };

                let status = '';
                let remaining = '';
                let isActive = false;

                if (now < startTime) {
                    status = 'Voting Not Started';
                    remaining = `Starts in ${formatTime(startTime)}`;
                    isActive = false;
                } else if (now > endTime) {
                    status = 'Voting Ended';
                    remaining = 'Election completed';
                    isActive = false;
                } else {
                    status = 'Voting Active';
                    remaining = `Ends in ${formatTime(endTime)}`;
                    isActive = true;
                }

                document.getElementById(`remainingTime-${index}`).textContent = remaining;
                document.getElementById(`electionStatus-${index}`).textContent = status;

                // Update all vote buttons for this election
                document.querySelectorAll(`[data-election-index="${index}"] .vote-btn`).forEach(button => {
                    button.disabled = !isActive;

                    // Add tooltip for disabled buttons
                    if (button.disabled) {
                        if (now < startTime) {
                            button.title = 'Voting has not started yet';
                        } else if (now > endTime) {
                            button.title = 'Voting period has ended';
                        }
                    } else {
                        button.removeAttribute('title');
                    }
                });
            });
        } catch (error) {
            console.error('Time update error:', error);
        }
    };

    // Voting form handling
    const handleVoteSubmission = (form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const button = form.querySelector('.vote-btn');
            if (button.disabled) {
                Swal.fire('Error!', 'Voting is not active for this election.', 'error');
                return;
            }

            const originalHTML = button.innerHTML;
            const candidateName = form.closest('.candidate-card').querySelector('.card-title').textContent;

            try {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing';

                const { isConfirmed } = await Swal.fire({
                    title: 'Confirm Your Vote',
                    html: `You are voting for <b>${candidateName}</b>. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, vote now!'
                });

                if (!isConfirmed) throw new Error('Vote cancelled');

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Vote submission failed');
                }

                await Swal.fire('Success!', 'Your vote has been recorded.', 'success');
                window.location.reload();
            } catch (error) {
                if (error.message !== 'Vote cancelled') {
                    Swal.fire('Error!', error.message || 'Failed to submit vote', 'error');
                }
            } finally {
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        });
    };

    // Initial setup
    updateTimeDisplays();
    setInterval(updateTimeDisplays, 1000);

    // Event listeners
    document.querySelectorAll('.vote-form').forEach(handleVoteSubmission);
    document.querySelectorAll('.view-profile').forEach(btn => {
        btn.addEventListener('click', () => {
            window.location.href = `/candidates/${btn.dataset.candidateId}`;
        });
    });
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@endsection
