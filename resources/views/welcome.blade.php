<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-VOTING') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Custom CSS -->
        <style>
            :root {
                --primary-color: #4f46e5;
                --primary-hover: #4338ca;
                --dark-bg: #1a202c;
                --light-bg: #f8fafc;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background-color: var(--light-bg);
                color: #2d3748;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .hero-section {
                position: relative;
                min-height: 100vh;
                background: url('/images/vote13.png') no-repeat center center;
                background-size: cover;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: 1;
            }

            .auth-card {
                position: relative;
                z-index: 2;
                background: rgba(255, 255, 255, 0.15); /* More transparent */
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                padding: 2.5rem;
                width: 100%;
                max-width: 400px;
                transition: all 0.3s ease;
            }

            .auth-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                background: rgba(255, 255, 255, 0.2);
            }

            .auth-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .auth-header h1 {
                font-weight: 600;
                color: white;
                margin-bottom: 0.5rem;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .auth-header p {
                color: rgba(255, 255, 255, 0.9);
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                padding: 0.5rem 1.5rem;
                font-weight: 500;
                transition: all 0.2s;
            }

            .btn-primary:hover {
                background-color: var(--primary-hover);
                border-color: var(--primary-hover);
                transform: translateY(-2px);
            }

            .btn-outline-primary {
                color: white;
                border-color: white;
            }

            .btn-outline-primary:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: white;
            }

            /* Animation */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .auth-card {
                animation: fadeIn 0.6s ease-out forwards;
            }

            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                padding: 1rem;
                text-align: center;
                color: white;
                z-index: 2;
                font-size: 0.9rem;
                opacity: 0.8;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .auth-card {
                    padding: 1.5rem;
                    margin: 1rem;
                    max-width: 90%;
                }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="hero-section">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Welcome to E-Voting</h1>
                    <p class="mb-4">Please sign in to continue</p>
                </div>

                <div class="d-grid gap-3">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i> Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'E-VOTING') }}</p>
            </div>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom JS -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add animation to buttons on hover
                const buttons = document.querySelectorAll('.btn');
                buttons.forEach(button => {
                    button.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-3px)';
                    });
                    button.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            });
        </script>
    </body>
</html>
