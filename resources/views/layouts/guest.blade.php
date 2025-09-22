<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BugTester') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="login-page">
        <div class="min-vh-100 d-flex align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <!-- Logo Section -->
                        <div class="text-center mb-4">
                            <a href="/" wire:navigate class="text-decoration-none login-logo">
                                @if(\App\Models\SystemSetting::hasLogo())
                                    <img src="{{ \App\Models\SystemSetting::getLogoUrl() }}"
                                         alt="{{ config('app.name', 'BugTester') }}"
                                         class="img-fluid mb-3"
                                         style="max-height: 80px;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center mb-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-bug text-white fs-2"></i>
                                        </div>
                                        <h2 class="mb-0 fw-bold text-white">{{ config('app.name', 'BugTester') }}</h2>
                                    </div>
                                @endif
                            </a>
                            <p class="text-white-50">Sign in to your account</p>
                        </div>

                        <!-- Login Form Card -->
                        <div class="card login-card">
                            <div class="card-body p-4">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
