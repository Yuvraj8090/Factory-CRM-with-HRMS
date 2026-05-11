<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Factory CRM') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=source-sans-3:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="hold-transition layout-top-nav">
        <div class="wrapper">
            <nav class="main-header navbar navbar-expand-md navbar-white navbar-light">
                <div class="container">
                    <a href="{{ route('home') }}" class="navbar-brand">
                    <span class="brand-text font-weight-light"><strong>Factory</strong> CRM</span>
                    </a>

                    <div class="ml-auto d-flex align-items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Open Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary mr-2">Sign In</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            <div class="content-wrapper" style="margin-left: 0;">
                <div class="content">
                    <div class="container py-5">
                        <div class="card app-hero-card">
                            <div class="row no-gutters">
                                <div class="col-lg-6 app-hero-panel p-5 d-flex flex-column justify-content-center">
                                    <span class="badge badge-light text-primary mb-3 align-self-start">AdminLTE Integrated</span>
                                    <h1 class="display-4 font-weight-bold">Factory CRM with HRMS</h1>
                                    <p class="lead mb-4">A unified AdminLTE interface for customer operations, finance tracking, payroll workflows, and employee management.</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @auth
                                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg mr-2 mb-2">Go to dashboard</a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-light btn-lg mr-2 mb-2">Sign in</a>
                                            @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg mb-2">Register</a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <div class="col-lg-6 bg-white p-5">
                                    <div class="row">
                                        <div class="col-sm-6 mb-4">
                                            <div class="info-box bg-light h-100">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">CRM</span>
                                                    <span class="text-muted">Leads, customers, and activities</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <div class="info-box bg-light h-100">
                                                <span class="info-box-icon bg-success"><i class="fas fa-money-check-dollar"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Finance</span>
                                                    <span class="text-muted">Quotations, invoices, and payments</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <div class="info-box bg-light h-100">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-user-tie"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">HRMS</span>
                                                    <span class="text-muted">Employees, attendance, and payroll</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <div class="info-box bg-light h-100">
                                                <span class="info-box-icon bg-danger"><i class="fas fa-sliders"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Settings</span>
                                                    <span class="text-muted">Master data and templates</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-primary mb-0">
                                        Responsive AdminLTE components now provide a consistent frontend and backend visual system across the application.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
