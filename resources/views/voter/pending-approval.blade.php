@extends('layouts.app')

@section('content')
<div class="review-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card review-card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-clock me-3 fs-4"></i>
                        <h4 class="mb-0">Profile Under Review</h4>
                    </div>
                </div>

                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2 fs-4"></i>
                            <div>
                                {{ session('status') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="review-content text-center py-4">
                        <div class="review-icon mb-4">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-hourglass-half fs-1"></i>
                            </div>
                        </div>
                        <h3 class="mb-4">Your Application is Being Reviewed</h3>

                        <div class="review-message bg-light p-4 rounded-3 mb-4">
                            <p class="lead mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Your voter profile has been submitted and is pending approval by the administrator.
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-bell text-primary me-2"></i>
                                You will be notified once your profile has been approved and you can participate in elections.
                            </p>
                        </div>

                        <div class="progress-container mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Review Progress</span>
                                <span>Pending</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width: 45%;"
                                     aria-valuenow="45"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-0 py-3 text-center">
                    <p class="text-muted mb-0">
                        <i class="fas fa-clock me-1"></i>
                        Typically takes 1-3 business days
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .review-container {
        background-color: #f8fafc;
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }

    .review-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .review-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .review-message {
        border-left: 4px solid #4e73df;
        background-color: rgba(78, 115, 223, 0.03);
    }

    .progress-container {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bar on page load
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            // Simulate progress animation
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 45) {
                    clearInterval(interval);
                } else {
                    width++;
                    progressBar.style.width = width + '%';
                    progressBar.setAttribute('aria-valuenow', width);
                }
            }, 30);
        }

        // Add hover effect to card
        const card = document.querySelector('.review-card');
        if (card) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 0.5rem 1rem rgba(0, 0, 0, 0.15)';
            });
        }
    });
</script>
@endpush
