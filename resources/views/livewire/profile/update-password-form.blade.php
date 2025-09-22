<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div>
    <div class="card-jira-header mb-4">
        <h3 class="heading-jira-4 mb-1">{{ __('Update Password') }}</h3>
        <p class="text-jira-muted mb-0">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <form wire:submit="updatePassword">
        <div class="row g-4">
            <div class="col-12">
                <label for="update_password_current_password" class="form-label">
                    <i class="fas fa-lock me-2 text-muted"></i>{{ __('Current Password') }}
                </label>
                <input wire:model="current_password"
                       id="update_password_current_password"
                       name="current_password"
                       type="password"
                       class="form-control @error('current_password') is-invalid @enderror"
                       autocomplete="current-password"
                       placeholder="Enter your current password">
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="update_password_password" class="form-label">
                    <i class="fas fa-key me-2 text-muted"></i>{{ __('New Password') }}
                </label>
                <input wire:model="password"
                       id="update_password_password"
                       name="password"
                       type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Enter your new password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="update_password_password_confirmation" class="form-label">
                    <i class="fas fa-check-double me-2 text-muted"></i>{{ __('Confirm Password') }}
                </label>
                <input wire:model="password_confirmation"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Confirm your new password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <!-- Success Message -->
                <div wire:loading.remove wire:target="updatePassword">
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
                         @password-updated.window="show = true; setTimeout(() => show = false, 3000)">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ __('Password updated successfully!') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" @click="show = false"></button>
                    </div>
                </div>

                <!-- Error Message -->
                <div wire:loading.remove wire:target="updatePassword">
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
                         @password-error.window="show = true; setTimeout(() => show = false, 5000)">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span x-text="$event.detail.message || 'An error occurred while updating your password.'"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" @click="show = false"></button>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary position-relative"
                            wire:loading.attr="disabled"
                            wire:target="updatePassword"
                            style="transition: all 0.3s ease;">
                        <span wire:loading.remove wire:target="updatePassword" class="d-flex align-items-center">
                            <i class="fas fa-save me-2"></i>{{ __('Save') }}
                        </span>
                        <span wire:loading wire:target="updatePassword" class="d-flex align-items-center">
                            <i class="fas fa-spinner fa-spin me-2"></i>{{ __('Updating...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
