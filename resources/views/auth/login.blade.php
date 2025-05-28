@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: url('/images/vote13.png') no-repeat center center; background-size: cover;">
    <div class="position-absolute w-100 h-100 bg-dark opacity-50"></div>

    <div class="glass-card position-relative p-4 p-md-5 rounded-4 shadow-lg" style="width: 100%; max-width: 500px;">
        <div class="text-center mb-4">
            <h1 class="text-white fw-bold mb-3">{{ __('Login') }}</h1>
            <p class="text-white-50">Sign in to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label text-white mb-2">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control glass-input @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-white mb-2">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control glass-input @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password">

                @error('password')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-white" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-white-50 text-decoration-none" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                    {{ __('Login') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        background: rgba(255, 255, 255, 0.2);
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .glass-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
    }

    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .btn-primary {
        background-color: rgba(79, 70, 229, 0.9);
        border: none;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background-color: rgba(67, 56, 202, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to form elements
        const formControls = document.querySelectorAll('.form-control, .btn');

        formControls.forEach(control => {
            control.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            control.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });

            if (control.classList.contains('form-control')) {
                control.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                });

                control.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            }
        });
    });
</script>
@endsection
