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

        <!-- Custom Sidebar CSS for desktop fixed height -->
        <style>
            .sidebar-nav {
                height: calc(100vh - 120px);
                overflow-y: auto;
            }
            .sidebar-nav::-webkit-scrollbar { width: 5px; }
            .sidebar-nav::-webkit-scrollbar-thumb { background: #495057; border-radius: 5px; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        @php
             $user = auth()->user();
            $userName = $user?->name ?? 'User';
            $userID = $user?->id ?? null;
            $userEmail = $user?->email ?? 'user@example.com';
            $initials = collect(explode(' ', $userName))
                ->filter()
                ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp
    </head>
    
    <body class="bg-light text-sm" data-auth-user-id="{{ $userID }}">
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
            $userID = $user?->id ?? null;
            $userEmail = $user?->email ?? 'user@example.com';
            $initials = collect(explode(' ', $userName))
                ->filter()
                ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <div class="d-flex min-vh-100">
            
            <!-- Sidebar (Offcanvas on mobile, fixed on desktop) -->
            <aside class="offcanvas-lg offcanvas-start bg-dark text-white shadow-sm flex-shrink-0" tabindex="-1" id="sidebarMenu" style="width: 260px;">
                <!-- Brand -->
                <div class="offcanvas-header border-bottom border-secondary p-3">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm me-2" style="width: 35px; height: 35px; font-weight: 600;">
                            FC
                        </span>
                        <span class="fs-5 fw-semibold">Factory CRM</span>
                    </a>
                    <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
                </div>

                <!-- User Panel -->
                <div class="p-3 border-bottom border-secondary d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center bg-light text-primary rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; font-weight: 600;">
                        {{ $initials }}
                    </span>
                    <div class="d-flex flex-column text-truncate">
                        <span class="fw-semibold text-truncate">{{ $userName }}</span>
                        <small class="text-white-50 text-truncate" style="font-size: 0.75rem;">{{ $userEmail }}</small>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="offcanvas-body p-0 sidebar-nav">
                    <ul class="nav nav-pills flex-column px-2 py-3 w-100 mb-auto">
                        @foreach ($navigation as $group => $items)
                            @php
                                $visibleItems = collect($items)->filter(fn (array $item) => ! $item['can'] || auth()->user()->can($item['can']));
                            @endphp

                            @if ($visibleItems->isNotEmpty())
                                <li class="nav-item mt-3 mb-1">
                                    <h6 class="px-3 text-uppercase text-white-50" style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ $group }}</h6>
                                </li>
                                @foreach ($visibleItems as $item)
                                    @php
                                        $active = collect($item['match'])->contains(fn (string $pattern) => request()->routeIs($pattern));
                                    @endphp
                                    <li class="nav-item mb-1">
                                        <a href="{{ route($item['route']) }}" class="nav-link text-white {{ $active ? 'active bg-primary shadow-sm' : 'opacity-75 hover-opacity-100' }}">
                                            <i class="fa-fw me-2 {{ $item['icon'] }}"></i>
                                            {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- Main Content Wrapper -->
            <div class="flex-grow-1 d-flex flex-column" style="min-width: 0;">
                
                <!-- Top Navbar -->
                <header class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top px-3 py-2">
                    <!-- Hamburger Toggle (Mobile) -->
                    <button class="btn btn-light d-lg-none me-2 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Top Links -->
                    <ul class="navbar-nav me-auto d-none d-md-flex flex-row gap-3">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link text-dark fw-medium">Dashboard</a>
                        </li>
                        
                    </ul>

                    <!-- Right Side Components -->
                    <div class="d-flex align-items-center ms-auto gap-3">
                        
                        <!-- Search Bar -->
                        <form class="d-none d-md-flex align-items-center">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 focus-ring-0" placeholder="Search...">
                            </div>
                        </form>

                        <!-- Date Badge -->
                        <span class="d-none d-xl-inline text-muted small border-start border-end px-3">
                            <i class="far fa-calendar-alt me-1"></i> {{ now()->format('D, d M Y') }}
                        </span>

                        <!-- Notifications Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="text-secondary position-relative text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-0" style="width: 280px;">
                                <li class="p-2 border-bottom fw-bold text-center bg-light">Notifications</li>
                                <li><a class="dropdown-item py-2 small" href="#">You have 3 new leads</a></li>
                                <li><a class="dropdown-item py-2 small" href="#">Invoice #402 paid</a></li>
                                <li class="p-2 text-center border-top"><a href="#" class="text-decoration-none small fw-semibold">View All</a></li>
                            </ul>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm me-2" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.8rem;">
                                    {{ $initials }}
                                </span>
                                <span class="d-none d-md-inline fw-medium">{{ $userName }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3">
                                <li class="text-center px-4 py-3 border-bottom bg-light">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        {{ $initials }}
                                    </span>
                                    <h6 class="mb-0">{{ $userName }}</h6>
                                    <small class="text-muted">{{ $userEmail }}</small>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 mt-1" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-cog me-2 text-muted"></i> Account Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="flex-grow-1 bg-light p-3 p-lg-4">
                    
                    <x-crud.flash-stack />

                    <!-- Page Header -->
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom">
                        @isset($header)
                            {{ $header }}
                        @else
                            <div>
                                <h1 class="h3 mb-0 text-dark">{{ config('app.name', 'Factory CRM') }}</h1>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 small">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                    </ol>
                                </nav>
                            </div>
                        @endisset
                    </div>

                    <!-- Page Content Slot -->
                    <!-- Note: Inside your views, wrap your tables/forms in Bootstrap cards: 
                         <div class="card shadow-sm border-0"><div class="card-body">...</div></div> -->
                    <div class="container-fluid px-0">
                        {{ $slot }}
                    </div>

                </main>

                <!-- Footer -->
                <footer class="bg-white border-top py-3 px-4 d-flex flex-column flex-md-row align-items-center justify-content-between text-muted small mt-auto">
                    <div>
                        <strong class="text-dark">{{ config('app.name', 'Factory CRM') }}</strong> &copy; {{ date('Y') }}. 
                        <span class="d-none d-sm-inline">All rights reserved.</span>
                    </div>
                   
                </footer>

            </div>
        </div>

        <div id="app-feedback" class="position-fixed top-0 end-0 p-3" style="z-index: 1080; max-width: 360px;"></div>

        <!-- Bootstrap 5 JS Bundle (includes Popper.js for dropdowns) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.AppLocalStore?.update('session', {
                    userId: @json($user?->id),
                    userName: @json($userName),
                    currentRoute: @json(optional(request()->route())->getName()),
                    currentUrl: window.location.pathname,
                    syncedAt: new Date().toISOString(),
                });
            });

            window.addEventListener('app-local-storage-error', (event) => {
                const container = document.getElementById('app-feedback');

                if (!container || !event.detail?.message) {
                    return;
                }

                const alert = document.createElement('div');
                alert.className = 'alert alert-warning shadow-sm border-0 mb-2';
                alert.innerHTML = `<strong>Saved data warning:</strong> ${event.detail.message}`;
                container.appendChild(alert);

                window.setTimeout(() => {
                    alert.remove();
                }, 4500);
            });
        </script>
        
        @stack('scripts')
    </body>
</html>
