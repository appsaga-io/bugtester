<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $search = '';

    public function search()
    {
        // Handle search functionality
        $this->dispatch('search-performed', $this->search);
    }
}; ?>

<div class="topbar-jira">
    <div class="topbar-content">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-md text-atlassian-gray-500 hover:text-atlassian-gray-700 hover:bg-atlassian-gray-100 focus:outline-none focus:ring-2 focus:ring-atlassian-blue-500">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Global Search -->
        <div class="search-container">
            <div class="search-input-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search bugs, projects, users..."
                       class="search-input"
                       @keydown.enter="search()">
                <div class="search-shortcut">
                    <kbd class="kbd">⌘</kbd>
                    <kbd class="kbd">K</kbd>
                </div>
            </div>
        </div>

        <!-- Right Side Actions -->
        <div class="topbar-actions">
            <!-- Quick Create Button -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="btn-jira btn-jira-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create
                </button>

                <!-- Quick Create Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-atlassian-gray-200 py-1 z-50">

                    @can('create-bugs')
                    <a href="{{ route('bugs.create') }}" wire:navigate
                       class="flex items-center px-4 py-2 text-sm text-atlassian-gray-700 hover:bg-atlassian-gray-50">
                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Report Bug
                    </a>
                    @endcan

                    @can('create-projects')
                    <a href="{{ route('projects.create') }}" wire:navigate
                       class="flex items-center px-4 py-2 text-sm text-atlassian-gray-700 hover:bg-atlassian-gray-50">
                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        New Project
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="relative p-2 text-atlassian-gray-500 hover:text-atlassian-gray-700 hover:bg-atlassian-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-atlassian-blue-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L16 7H4.828z" />
                    </svg>
                    <!-- Notification Badge -->
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-atlassian-gray-200 py-1 z-50">

                    <div class="px-4 py-3 border-b border-atlassian-gray-100">
                        <h3 class="text-sm font-semibold text-atlassian-gray-900">Notifications</h3>
                    </div>

                    <div class="max-h-64 overflow-y-auto">
                        <div class="px-4 py-3 hover:bg-atlassian-gray-50 border-b border-atlassian-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-atlassian-gray-900">New bug reported in Project Alpha</p>
                                    <p class="text-xs text-atlassian-gray-500 mt-1">2 minutes ago</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 hover:bg-atlassian-gray-50 border-b border-atlassian-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-atlassian-gray-900">Bug #123 has been resolved</p>
                                    <p class="text-xs text-atlassian-gray-500 mt-1">1 hour ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-2 border-t border-atlassian-gray-100">
                        <a href="#" class="text-sm text-atlassian-blue-600 hover:text-atlassian-blue-800">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center space-x-3 p-2 text-atlassian-gray-700 hover:bg-atlassian-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-atlassian-blue-500">
                    <div class="w-8 h-8 bg-atlassian-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-sm font-medium" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="text-xs text-atlassian-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                    <svg class="w-4 h-4 text-atlassian-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-atlassian-gray-200 py-1 z-50">

                    <a href="{{ route('profile') }}" wire:navigate
                       class="flex items-center px-4 py-2 text-sm text-atlassian-gray-700 hover:bg-atlassian-gray-50">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>

                    <a href="#" class="flex items-center px-4 py-2 text-sm text-atlassian-gray-700 hover:bg-atlassian-gray-50">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>

                    <div class="border-t border-atlassian-gray-100 my-1"></div>

                    <button wire:click="logout" class="w-full text-left flex items-center px-4 py-2 text-sm text-atlassian-gray-700 hover:bg-atlassian-gray-50">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign out
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
