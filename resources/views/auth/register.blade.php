@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: url('/images/vote13.png') no-repeat center center; background-size: cover;">
    <div class="position-absolute w-100 h-100 bg-dark opacity-50"></div>

    <div class="glass-card position-relative p-4 p-md-5 rounded-4 shadow-lg" style="width: 100%; max-width: 500px;">
        <div class="text-center mb-4">
            <h1 class="text-white fw-bold mb-3">{{ __('Register') }}</h1>
            <p class="text-white-50">Create your account</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label text-white mb-2">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control glass-input @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="form-label text-white mb-2">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control glass-input @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-white mb-2">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control glass-input @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password">

                @error('password')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="form-label text-white mb-2">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control glass-input"
                       name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="mb-4">
                <label for="role" class="form-label text-white mb-2">Register As</label>
                <select id="role" name="role" class="form-select glass-input" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="voter" {{ old('role') == 'voter' ? 'selected' : '' }}>Voter</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                    {{ __('Register') }}
                </button>
            </div>

            <div class="text-center mt-3">
                <p class="text-white-50">Already have an account?
                    <a href="{{ route('login') }}" class="text-white text-decoration-underline">Login here</a>
                </p>
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

    .glass-input, .form-select.glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .glass-input:focus, .form-select.glass-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
    }

    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-select.glass-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='white' fill-opacity='0.7' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
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
        const formControls = document.querySelectorAll('.form-control, .btn, .form-select');

        formControls.forEach(control => {
            control.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            control.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });

            if (control.classList.contains('form-control') || control.classList.contains('form-select')) {
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
