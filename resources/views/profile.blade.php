<x-app-layout>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Profile')"
            :description="'Manage your account settings and profile information.'"
        >
            <a href="{{ route('dashboard') }}" wire:navigate class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Dashboard
            </a>
        </x-page-header>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="row g-4">
                    <!-- Profile Information -->
                    <div class="col-12">
                        <div class="card-jira">
                            <div class="card-jira-body">
                                <livewire:profile.update-profile-information-form />
                            </div>
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="col-12">
                        <div class="card-jira">
                            <div class="card-jira-body">
                                <livewire:profile.update-password-form />
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="col-12">
                        <div class="card-jira">
                            <div class="card-jira-body">
                                <livewire:profile.delete-user-form />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
