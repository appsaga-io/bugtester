<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
    <div class="card-jira-header mb-4">
        <h3 class="heading-jira-4 mb-1">{{ __('Profile Information') }}</h3>
        <p class="text-jira-muted mb-0">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </div>

    <form wire:submit="updateProfileInformation">
        <div class="row g-4">
            <div class="col-12">
                <label for="name" class="form-label">
                    <i class="fas fa-user me-2 text-muted"></i>{{ __('Name') }}
                </label>
                <input wire:model="name"
                       id="name"
                       name="name"
                       type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       required
                       autofocus
                       autocomplete="name"
                       placeholder="Enter your full name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2 text-muted"></i>{{ __('Email') }}
                </label>
                <input wire:model="email"
                       id="email"
                       name="email"
                       type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       required
                       autocomplete="username"
                       placeholder="Enter your email address">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-3">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>{{ __('Your email address is unverified.') }}</strong>
                                <button wire:click.prevent="sendVerification"
                                        class="btn btn-link p-0 ms-2 text-decoration-none">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </div>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="col-12">
                <!-- Success Message -->
                <div wire:loading.remove wire:target="updateProfileInformation">
                    <div class="alert alert-success alert-dismissible fade show d-none"
                         role="alert"
                         x-data="{ show: false }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         @profile-updated.window="show = true; setTimeout(() => show = false, 3000)">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ __('Profile updated successfully!') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" @click="show = false"></button>
                    </div>
                </div>

                <!-- Error Message -->
                <div wire:loading.remove wire:target="updateProfileInformation">
                    <div class="alert alert-danger alert-dismissible fade show d-none"
                         role="alert"
                         x-data="{ show: false }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         @profile-error.window="show = true; setTimeout(() => show = false, 5000)">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span x-text="$event.detail.message || 'An error occurred while updating your profile.'"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" @click="show = false"></button>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary position-relative"
                            wire:loading.attr="disabled"
                            wire:target="updateProfileInformation"
                            style="transition: all 0.3s ease;">
                        <span wire:loading.remove wire:target="updateProfileInformation" class="d-flex align-items-center">
                            <i class="fas fa-save me-2"></i>{{ __('Save') }}
                        </span>
                        <span wire:loading wire:target="updateProfileInformation" class="d-flex align-items-center">
                            <i class="fas fa-spinner fa-spin me-2"></i>{{ __('Saving...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
