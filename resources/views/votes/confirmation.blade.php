@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Animated Success Alert -->
    <div class="alert alert-success alert-dismissible fade show shadow-lg animate__animated animate__fadeInDown" role="alert">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle fa-2x me-3"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="alert-heading mb-2"><strong>Vote Submitted Successfully!</strong></h4>
                <p class="mb-0">{{ session('success') }}</p>
                <hr>
                <p class="mb-0 small text-muted">Your vote has been securely recorded in the system.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <!-- Enhanced Return Button with Animation -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm hover-effect">
            <i class="fas fa-arrow-left me-2"></i> Return to Dashboard
        </a>


    </div>
</div>

@push('styles')
<!-- Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
<style>
    /* Custom styling */
    .hover-effect {
        transition: all 0.3s ease;
        transform: translateY(0);
        position: relative;
        overflow: hidden;
    }

    .hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 123, 255, 0.2) !important;
    }

    .hover-effect:active {
        transform: translateY(1px);
    }

    .hover-effect::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .hover-effect:hover::after {
        transform: translateX(100%);
    }

    .alert-success {
        border-left: 5px solid #28a745;
        border-radius: 0.5rem;
        background: linear-gradient(to right, rgba(40, 167, 69, 0.1), white);
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }

    .rounded-pill {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
</style>
@endpush

@push('scripts')
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const x = e.clientX - e.target.getBoundingClientRect().left;
                const y = e.clientY - e.target.getBoundingClientRect().top;

                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 1000);
            });
        });

        // Confetti effect on page load
        setTimeout(() => {
            if (window.confetti) {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#28a745', '#218838', '#5cb85c']
                });
            }
        }, 500);
    });
</script>

<!-- Confetti JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<style>
    /* Ripple effect style */
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.7);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>
@endpush
@endsection
