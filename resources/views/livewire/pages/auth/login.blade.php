<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit="login">
        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">
                <i class="fas fa-envelope me-2 text-muted"></i>{{ __('Email Address') }}
            </label>
            <input wire:model="form.email"
                   id="email"
                   type="email"
                   name="email"
                   class="form-control @error('form.email') is-invalid @enderror"
                   placeholder="Enter your email"
                   required
                   autofocus
                   autocomplete="username">
            @error('form.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">
                <i class="fas fa-lock me-2 text-muted"></i>{{ __('Password') }}
            </label>
            <input wire:model="form.password"
                   id="password"
                   type="password"
                   name="password"
                   class="form-control @error('form.password') is-invalid @enderror"
                   placeholder="Enter your password"
                   required
                   autocomplete="current-password">
            @error('form.password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check">
                <input wire:model="form.remember"
                       id="remember"
                       type="checkbox"
                       class="form-check-input"
                       name="remember">
                <label for="remember" class="form-check-label text-muted">
                    {{ __('Remember me') }}
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Log in') }}
            </button>
        </div>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="text-center">
                <a href="{{ route('password.request') }}"
                   wire:navigate
                   class="text-decoration-none text-muted">
                    <i class="fas fa-key me-1"></i>{{ __('Forgot your password?') }}
                </a>
            </div>
        @endif
    </form>
</div>
