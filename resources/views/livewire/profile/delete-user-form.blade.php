<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="card-jira-header mb-4">
        <h3 class="heading-jira-4 mb-1 text-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Delete Account') }}
        </h3>
        <p class="text-jira-muted mb-0">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>

    <div class="d-flex justify-content-end">
        <button type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#confirmUserDeletion">
            <i class="fas fa-trash me-2"></i>{{ __('Delete Account') }}
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger" id="confirmUserDeletionLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Delete Account') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="deleteUser">
                    <div class="modal-body">
                        <div class="alert alert-danger d-flex align-items-start" role="alert">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <div>
                                <strong>{{ __('Warning!') }}</strong>
                                <p class="mb-0 mt-1">{{ __('Are you sure you want to delete your account?') }}</p>
                            </div>
                        </div>

                        <p class="text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2 text-muted"></i>{{ __('Password') }}
                            </label>
                            <input wire:model="password"
                                   id="password"
                                   name="password"
                                   type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{ __('Enter your password to confirm') }}"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>{{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
