<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Factory CRM') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=source-sans-3:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light text-sm d-flex flex-column min-vh-100">
        
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top border-bottom">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center text-dark text-decoration-none">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm me-2" style="width: 35px; height: 35px; font-weight: 600; font-size: 0.9rem;">
                        FC
                    </span>
                    <span class="fw-light"><strong>Factory</strong> CRM</span>
                </a>

                <div class="ms-auto d-flex align-items-center gap-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-3 shadow-sm">Open Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 shadow-sm">Sign In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 shadow-sm">Create Account</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container">
                <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                    <div class="row g-0">
                        
                        <!-- Left Hero Section -->
                        <div class="col-lg-6 bg-primary text-white p-4 p-md-5 d-flex flex-column justify-content-center">
                            <span class="badge bg-light text-primary mb-3 align-self-start py-2 px-3 rounded-pill shadow-sm">Bootstrap 5 Integrated</span>
                            <h1 class="display-5 fw-bold mb-3">Factory CRM with HRMS</h1>
                            <p class="lead mb-4 text-white-50">A unified interface for customer operations, finance tracking, payroll workflows, and employee management.</p>
                            <div class="d-flex flex-wrap gap-3">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg shadow-sm px-4 fw-medium">Go to dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-light btn-lg shadow-sm px-4 fw-medium">Sign in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 fw-medium">Register</a>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <!-- Right Info Boxes Section -->
                        <div class="col-lg-6 bg-white p-4 p-md-5">
                            <div class="row g-4 mb-4">
                                
                                <!-- CRM Box -->
                                <div class="col-sm-6">
                                    <div class="card h-100 bg-light border-0 shadow-sm rounded-3 hover-shadow transition">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold text-dark">CRM</h6>
                                                <p class="mb-0 text-muted small">Leads, customers, & activities</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Finance Box -->
                                <div class="col-sm-6">
                                    <div class="card h-100 bg-light border-0 shadow-sm rounded-3">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <i class="fas fa-money-check-dollar"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold text-dark">Finance</h6>
                                                <p class="mb-0 text-muted small">Quotations, invoices, & payments</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- HRMS Box -->
                                <div class="col-sm-6">
                                    <div class="card h-100 bg-light border-0 shadow-sm rounded-3">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-warning text-dark rounded-circle shadow-sm" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold text-dark">HRMS</h6>
                                                <p class="mb-0 text-muted small">Employees, attendance, & payroll</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings Box -->
                                <div class="col-sm-6">
                                    <div class="card h-100 bg-light border-0 shadow-sm rounded-3">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-danger text-white rounded-circle shadow-sm" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <i class="fas fa-sliders"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold text-dark">Settings</h6>
                                                <p class="mb-0 text-muted small">Master data & templates</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>