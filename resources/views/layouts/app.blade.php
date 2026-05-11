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
        @stack('styles')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
        @php
            $navigation = [
                'CRM' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => ['dashboard'], 'can' => 'view dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['label' => 'Leads', 'route' => 'crm.leads.index', 'match' => ['crm.leads.*'], 'can' => 'view leads', 'icon' => 'fas fa-user-plus'],
                    ['label' => 'Activities', 'route' => 'crm.activities.index', 'match' => ['crm.activities.*'], 'can' => 'view activities', 'icon' => 'fas fa-clipboard-list'],
                    ['label' => 'Customers', 'route' => 'crm.customers.index', 'match' => ['crm.customers.*'], 'can' => 'view customers', 'icon' => 'fas fa-building'],
                    ['label' => 'Sales Teams', 'route' => 'crm.sales-teams.index', 'match' => ['crm.sales-teams.*'], 'can' => 'view sales-teams', 'icon' => 'fas fa-users'],
                ],
                'Finance' => [
                    ['label' => 'Quotations', 'route' => 'finance.quotations.index', 'match' => ['finance.quotations.*'], 'can' => 'view quotations', 'icon' => 'fas fa-file-signature'],
                    ['label' => 'Invoices', 'route' => 'finance.invoices.index', 'match' => ['finance.invoices.*'], 'can' => 'view invoices', 'icon' => 'fas fa-file-invoice'],
                    ['label' => 'Payments', 'route' => 'finance.payments.index', 'match' => ['finance.payments.*'], 'can' => 'view payments', 'icon' => 'fas fa-money-bill-wave'],
                    ['label' => 'Debit Notes', 'route' => 'finance.debit-notes.index', 'match' => ['finance.debit-notes.*'], 'can' => 'view debit-notes', 'icon' => 'fas fa-file-alt'],
                ],
                'HRMS' => [
                    ['label' => 'Employees', 'route' => 'hrms.employees.index', 'match' => ['hrms.employees.*'], 'can' => 'view employees', 'icon' => 'fas fa-briefcase'],
                    ['label' => 'Attendance', 'route' => 'hrms.attendances.index', 'match' => ['hrms.attendances.*'], 'can' => 'view attendances', 'icon' => 'fas fa-calendar-check'],
                    ['label' => 'Departments', 'route' => 'hrms.departments.index', 'match' => ['hrms.departments.*'], 'can' => 'view departments', 'icon' => 'fas fa-sitemap'],
                    ['label' => 'Designations', 'route' => 'hrms.designations.index', 'match' => ['hrms.designations.*'], 'can' => 'view designations', 'icon' => 'fas fa-tags'],
                    ['label' => 'Leave Types', 'route' => 'hrms.leave-types.index', 'match' => ['hrms.leave-types.*'], 'can' => 'view leave-types', 'icon' => 'fas fa-folder-open'],
                    ['label' => 'Leave Requests', 'route' => 'hrms.leave-requests.index', 'match' => ['hrms.leave-requests.*'], 'can' => 'view leave-requests', 'icon' => 'fas fa-file-signature'],
                    ['label' => 'Payroll', 'route' => 'hrms.payrolls.index', 'match' => ['hrms.payrolls.*'], 'can' => 'view payrolls', 'icon' => 'fas fa-chart-bar'],
                ],
                'Settings' => [
                    ['label' => 'Categories', 'route' => 'settings.categories.index', 'match' => ['settings.categories.*'], 'can' => 'view categories', 'icon' => 'fas fa-folder'],
                    ['label' => 'Item Masters', 'route' => 'settings.item-masters.index', 'match' => ['settings.item-masters.*'], 'can' => 'view item-masters', 'icon' => 'fas fa-cubes'],
                    ['label' => 'WhatsApp Templates', 'route' => 'settings.whats-app-templates.index', 'match' => ['settings.whats-app-templates.*'], 'can' => 'view whats-app-templates', 'icon' => 'fab fa-whatsapp'],
                    ['label' => 'Profile', 'route' => 'profile.edit', 'match' => ['profile.*'], 'can' => null, 'icon' => 'fas fa-user-cog'],
                ],
            ];

            $user = auth()->user();
            $userName = $user?->name ?? 'User';
            $userEmail = $user?->email ?? 'user@example.com';
            $initials = collect(explode(' ', $userName))
                ->filter()
                ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <div class="wrapper">
            <x-crud.flash-stack />

            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{ route('home') }}" class="nav-link">Frontend</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item d-none d-md-flex align-items-center pr-3 text-muted">
                        <i class="far fa-calendar-alt mr-2"></i>{{ now()->format('D, d M Y') }}
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white font-weight-bold" style="width: 2.25rem; height: 2.25rem;">
                                {{ $initials }}
                            </span>
                            <span class="d-none d-md-inline ml-2">{{ $userName }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <li class="user-header bg-primary">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white text-primary font-weight-bold mb-2" style="width: 4rem; height: 4rem; font-size: 1.25rem;">
                                    {{ $initials }}
                                </span>
                                <p class="mb-0">{{ $userName }}</p>
                                <small>{{ $userEmail }}</small>
                            </li>
                            <li class="user-footer">
                                <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="float-right">
                                    @csrf
                                    <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <span class="brand-image img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-primary text-white font-weight-bold" style="opacity: 1; width: 33px; height: 33px;">
                        FC
                    </span>
                    <span class="brand-text font-weight-light">Factory CRM</span>
                </a>

                <div class="sidebar">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <span class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-white text-primary font-weight-bold" style="width: 34px; height: 34px;">
                                {{ $initials }}
                            </span>
                        </div>
                        <div class="info">
                            <a href="{{ route('profile.edit') }}" class="d-block">{{ $userName }}</a>
                            <small class="text-muted">{{ $userEmail }}</small>
                        </div>
                    </div>

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                            @foreach ($navigation as $group => $items)
                                @php
                                    $visibleItems = collect($items)->filter(fn (array $item) => ! $item['can'] || auth()->user()->can($item['can']));
                                @endphp

                                @if ($visibleItems->isNotEmpty())
                                    <li class="nav-header text-uppercase">{{ $group }}</li>
                                    @foreach ($visibleItems as $item)
                                        @php
                                            $active = collect($item['match'])->contains(fn (string $pattern) => request()->routeIs($pattern));
                                        @endphp
                                        <li class="nav-item">
                                            <a href="{{ route($item['route']) }}" class="nav-link {{ $active ? 'active' : '' }}">
                                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                                <p>{{ $item['label'] }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        @isset($header)
                            {{ $header }}
                        @else
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0">{{ config('app.name', 'Factory CRM') }}</h1>
                                </div>
                            </div>
                        @endisset
                    </div>
                </div>

                <section class="content pb-4">
                    <div class="container-fluid">
                        {{ $slot }}
                    </div>
                </section>
            </div>

            <footer class="main-footer">
                <strong>{{ config('app.name', 'Factory CRM') }}</strong>
                <span class="text-muted ml-2">AdminLTE-powered CRM, finance, and HR operations workspace.</span>
                <div class="float-right d-none d-sm-inline-block">
                    <b>UI</b> Unified frontend and backend
                </div>
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
