@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="row justify-content-center">
        <div class="col-xxl-10">
            <!-- Dashboard Header Card - Fixed Visibility -->
            <div class="card dashboard-header-card mb-4 border-0 shadow-sm animate__animated animate__fadeIn">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-shield me-3 fs-4"></i>
                        <h4 class="mb-0 fw-bold text-uppercase">Voter Dashboard</h4>
                    </div>
                    @if(auth()->user()->unreadNotifications->count())
                        <span class="badge bg-danger rounded-pill px-3 py-2 pulse">
                            <i class="fas fa-bell me-1"></i> {{ auth()->user()->unreadNotifications->count() }} New
                        </span>
                    @endif
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Welcome Section -->
                    <div class="welcome-section mb-4 p-4 rounded-3 animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #4e73df;">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-gradient-primary text-white me-3">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="mb-1">Welcome, {{ auth()->user()->name }}!</h3>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-info-circle me-1 text-info"></i>
                                    You are logged in as a voter. Here you can view upcoming elections and notifications.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Section -->
                    @if(auth()->user()->unreadNotifications->count())
                    <div class="notifications-section mb-5 animate__animated animate__fadeInUp">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 text-primary"><i class="fas fa-bell me-2"></i>Your Notifications</h4>
                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary hover-effect">
                                    <i class="fas fa-check-double me-1"></i> Mark all as read
                                </button>
                            </form>
                        </div>

                        <div class="list-group shadow-sm">
                            @foreach(auth()->user()->unreadNotifications as $notification)
                            <div class="list-group-item list-group-item-action notification-item animate__animated animate__fadeIn">
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-bell text-warning me-2"></i>
                                            <strong class="me-2">{{ $notification->data['title'] ?? 'Notification' }}</strong>
                                            <span class="badge bg-info">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mb-2 ps-4">{{ $notification->data['message'] }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 ps-4">
                                    <div class="d-flex">
                                        @if(isset($notification->data['link']))
                                        <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-primary me-2 hover-effect">
                                            <i class="fas fa-external-link-alt me-1"></i> {{ $notification->data['action'] ?? 'View' }}
                                        </a>
                                        @endif
                                        <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success hover-effect">
                                                <i class="fas fa-check me-1"></i> Mark as read
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Status Card -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <div class="card status-card h-100 border-0 shadow-sm animate__animated animate__fadeInUp">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-gradient-success text-white me-3">
                                            <i class="fas fa-vote-yea"></i>
                                        </div>
                                        <h5 class="mb-0">Your Voting Status</h5>
                                    </div>
                                    @if(auth()->user()->voterProfile->is_approved)
                                        <div class="alert alert-success d-flex align-items-center">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <div>
                                                <strong>Approved Voter</strong>
                                                <p class="mb-0">You can participate in upcoming elections.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning d-flex align-items-center">
                                            <i class="fas fa-clock me-2"></i>
                                            <div>
                                                <strong>Pending Approval</strong>
                                                <p class="mb-0">Your voter registration is under review.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Elections Section -->
                    <div class="upcoming-elections animate__animated animate__fadeIn">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h4 class="mb-0 text-primary"><i class="fas fa-calendar-alt me-2"></i>Upcoming Elections</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card action-card h-100 border-start border-4 border-primary">
                                            <div class="card-body text-center p-4">
                                                <div class="icon-wrapper bg-primary bg-opacity-10 text-primary mb-3">
                                                    <i class="fas fa-vote-yea fa-2x"></i>
                                                </div>
                                                <h5 class="mb-3">View Elections</h5>
                                                <p class="text-muted mb-4">Vote for your preferred candidate</p>
                                                <div class="progress mb-3" style="height: 10px;">
                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <p class="small text-muted mb-3">65% of voters have participated</p>
                                                <a href="{{ route('voter.elections.pending') }}" class="btn btn-primary px-4 hover-effect">
                                                    <i class="fas fa-external-link-alt me-2"></i> View Candidates
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<style>
    /* Dashboard Header Fixes */
    .dashboard-header-card .card-header {
        padding: 1rem 1.5rem;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .dashboard-header-card .card-header h4 {
        font-size: 1.5rem;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    /* Base Dashboard Styles */
    .dashboard-container {
        background-color: #f8fafc;
        padding: 2rem 0;
    }

    .dashboard-header-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .welcome-section {
        border-left: 4px solid #4e73df;
        transition: all 0.3s ease;
    }

    .notification-item {
        border-left: 3px solid #4e73df;
        transition: all 0.3s ease;
        margin-bottom: 8px;
        border-radius: 8px !important;
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .action-card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    .hover-effect {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .hover-effect:after {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .hover-effect:hover:after {
        left: 100%;
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .progress-bar-animated {
        animation: progress-bar-stripes 1s linear infinite;
    }

    @keyframes progress-bar-stripes {
        0% {
            background-position: 1rem 0;
        }
        100% {
            background-position: 0 0;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Notification items animation
        const notifications = document.querySelectorAll('.notification-item');
        notifications.forEach(notification => {
            notification.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            });
            notification.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'none';
            });
        });

        // Confetti effect for approved voters
        @if(auth()->user()->voterProfile->is_approved)
        setTimeout(() => {
            if (typeof confetti === 'function') {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#4e73df', '#1cc88a', '#f6c23e']
                });
            }
        }, 1000);
        @endif
    });
</script>

<!-- Optional Confetti JS -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
@endpush
