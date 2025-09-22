<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AppSaga Solutions') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light d-flex flex-column min-vh-100">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white border-bottom">
                <div class="container-fluid py-4">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-4 flex-grow-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top mt-auto">
                <div class="container-fluid py-4">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="d-flex align-items-center mb-3">
                                @if(\App\Models\SystemSetting::hasLogo())
                                    <img src="{{ \App\Models\SystemSetting::getLogoUrl() }}"
                                         alt="Logo"
                                         class="me-3"
                                         style="height: 32px;">
                                @else
                                    <div class="bg-primary rounded me-3 d-flex align-items-center justify-content-center"
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-bug text-white"></i>
                                    </div>
                                @endif
                                <h5 class="mb-0 fw-bold text-primary">{{ config('app.name', 'BugTester') }}</h5>
                            </div>
                            <p class="text-muted mb-0">
                                Professional bug tracking and project management solution for modern development teams.
                            </p>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <h6 class="fw-semibold mb-3">Product</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="{{ route('dashboard') }}" wire:navigate class="text-muted text-decoration-none">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('bugs.index') }}" wire:navigate class="text-muted text-decoration-none">
                                        <i class="fas fa-bug me-2"></i>Bug Tracker
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('projects.index') }}" wire:navigate class="text-muted text-decoration-none">
                                        <i class="fas fa-project-diagram me-2"></i>Projects
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('users.index') }}" wire:navigate class="text-muted text-decoration-none">
                                        <i class="fas fa-users me-2"></i>Team
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <h6 class="fw-semibold mb-3">Features</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="text-muted">
                                        <i class="fas fa-columns me-2"></i>Kanban Board
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">
                                        <i class="fas fa-tasks me-2"></i>Task Management
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">
                                        <i class="fas fa-chart-line me-2"></i>Analytics
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <h6 class="fw-semibold mb-3">Support</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-question-circle me-2"></i>Help Center
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-book me-2"></i>Documentation
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-comments me-2"></i>Contact Us
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-life-ring me-2"></i>Support
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <h6 class="fw-semibold mb-3">Company</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-info-circle me-2"></i>About Us
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-shield-alt me-2"></i>Privacy Policy
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-file-contract me-2"></i>Terms of Service
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-muted text-decoration-none">
                                        <i class="fas fa-envelope me-2"></i>Contact
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="text-muted mb-0 small">
                                © {{ date('Y') }} {{ config('app.name', 'BugTester') }}. All rights reserved.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end gap-3 social-links">
                                <a href="#" class="text-muted">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-muted">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="#" class="text-muted">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="#" class="text-muted">
                                    <i class="fab fa-discord"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
    </body>
</html>
