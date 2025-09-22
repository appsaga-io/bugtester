<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="sidebar-jira-container">
    <!-- Logo Section -->
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
            @php
                $logoPath = \App\Models\SystemSetting::get('logo_path');
            @endphp
            @if($logoPath && \Storage::disk('public')->exists($logoPath))
                <img src="{{ \Storage::disk('public')->url($logoPath) }}"
                     alt="AppSaga Solutions"
                     class="h-8 w-auto object-contain">
            @else
                <x-appsaga-logo class="h-8 w-auto" />
            @endif
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>

            <a href="{{ route('dashboard') }}" wire:navigate
               class="nav-item {{ request()->routeIs('dashboard') ? 'nav-item-active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            @can('view-projects')
            <a href="{{ route('projects.index') }}" wire:navigate
               class="nav-item {{ request()->routeIs('projects.*') ? 'nav-item-active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="nav-label">Projects</span>
            </a>
            @endcan
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Issues</div>

            @can('view-bugs')
            <a href="{{ route('bugs.index') }}" wire:navigate
               class="nav-item {{ request()->routeIs('bugs.*') && !request()->routeIs('bugs.kanban') ? 'nav-item-active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span class="nav-label">Bugs</span>
            </a>

            <a href="{{ route('bugs.kanban') }}" wire:navigate
               class="nav-item {{ request()->routeIs('bugs.kanban') ? 'nav-item-active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
                <span class="nav-label">Kanban</span>
            </a>
            @endcan
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Administration</div>

            @can('view-users')
            <a href="{{ route('users.index') }}" wire:navigate
               class="nav-item {{ request()->routeIs('users.*') ? 'nav-item-active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <span class="nav-label">Users</span>
            </a>

            <a href="{{ route('admin.logo') }}" wire:navigate
               class="nav-item {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="nav-label">Settings</span>
            </a>
            @endcan
        </div>
    </nav>

    <!-- User Profile Section -->
    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="user-info">
                <div class="user-name" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>

        <div class="user-actions">
            <a href="{{ route('profile') }}" wire:navigate class="user-action-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </a>
            <button wire:click="logout" class="user-action-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </div>
    </div>
</div>
