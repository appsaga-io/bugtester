<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary" href="{{ route('dashboard') }}" wire:navigate>
            @if(\App\Models\SystemSetting::hasLogo())
                <img src="{{ \App\Models\SystemSetting::getLogoUrl() }}"
                     alt="{{ config('app.name', 'BugTester') }}"
                     height="32" class="me-2">
            @else
                <div class="bg-primary rounded me-2 d-flex align-items-center justify-content-center"
                     style="width: 32px; height: 32px;">
                    <i class="fas fa-bug text-white"></i>
                </div>
            @endif
            <span class="d-none d-sm-inline">{{ config('app.name', 'BugTester') }}</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }} d-flex align-items-center" href="{{ route('dashboard') }}" wire:navigate>
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                @can('view-projects')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('projects.*') ? 'active fw-semibold' : '' }} d-flex align-items-center" href="{{ route('projects.index') }}" wire:navigate>
                        <i class="fas fa-project-diagram me-2"></i>
                        <span>{{ __('Projects') }}</span>
                    </a>
                </li>
                @endcan

                @can('view-bugs')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bugs.*') && !request()->routeIs('bugs.kanban') ? 'active fw-semibold' : '' }} d-flex align-items-center" href="{{ route('bugs.index') }}" wire:navigate>
                        <i class="fas fa-bug me-2"></i>
                        <span>{{ __('Bugs') }}</span>
                    </a>
                </li>
                @endcan

                @can('view-bugs')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bugs.kanban') ? 'active fw-semibold' : '' }} d-flex align-items-center" href="{{ route('bugs.kanban') }}" wire:navigate>
                        <i class="fas fa-columns me-2"></i>
                        <span>{{ __('Kanban') }}</span>
                    </a>
                </li>
                @endcan

                @can('view-users')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active fw-semibold' : '' }} d-flex align-items-center" href="{{ route('users.index') }}" wire:navigate>
                        <i class="fas fa-users me-2"></i>
                        <span>{{ __('Users') }}</span>
                    </a>
                </li>
                @endcan

                @can('view-users')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'active fw-semibold' : '' }} d-flex align-items-center" href="{{ route('admin.logo') }}" wire:navigate>
                        <i class="fas fa-image me-2"></i>
                        <span>{{ __('Logo') }}</span>
                    </a>
                </li>
                @endcan
            </ul>

            <!-- User Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <span class="text-white fw-bold small">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <span class="fw-medium" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}" wire:navigate><i class="fas fa-user me-2"></i>{{ __('Profile') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item d-flex align-items-center text-danger" href="#" wire:click="logout"><i class="fas fa-sign-out-alt me-2"></i>{{ __('Log Out') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
