<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Factory CRM') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 font-[Manrope] text-slate-900 antialiased">
        <x-crud.flash-stack />

        @php
            $navigation = [
                'CRM' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => ['dashboard'], 'can' => 'view dashboard', 'icon' => 'home'],
                    ['label' => 'Leads', 'route' => 'crm.leads.index', 'match' => ['crm.leads.*'], 'can' => 'view leads', 'icon' => 'user-plus'],
                    ['label' => 'Activities', 'route' => 'crm.activities.index', 'match' => ['crm.activities.*'], 'can' => 'view activities', 'icon' => 'clipboard'],
                    ['label' => 'Customers', 'route' => 'crm.customers.index', 'match' => ['crm.customers.*'], 'can' => 'view customers', 'icon' => 'building'],
                    ['label' => 'Sales Teams', 'route' => 'crm.sales-teams.index', 'match' => ['crm.sales-teams.*'], 'can' => 'view sales-teams', 'icon' => 'users'],
                ],
                'Finance' => [
                    ['label' => 'Quotations', 'route' => 'finance.quotations.index', 'match' => ['finance.quotations.*'], 'can' => 'view quotations', 'icon' => 'document'],
                    ['label' => 'Invoices', 'route' => 'finance.invoices.index', 'match' => ['finance.invoices.*'], 'can' => 'view invoices', 'icon' => 'receipt'],
                    ['label' => 'Payments', 'route' => 'finance.payments.index', 'match' => ['finance.payments.*'], 'can' => 'view payments', 'icon' => 'currency'],
                    ['label' => 'Debit Notes', 'route' => 'finance.debit-notes.index', 'match' => ['finance.debit-notes.*'], 'can' => 'view debit-notes', 'icon' => 'document'],
                ],
                'HRMS' => [
                    ['label' => 'Employees', 'route' => 'hrms.employees.index', 'match' => ['hrms.employees.*'], 'can' => 'view employees', 'icon' => 'briefcase'],
                    ['label' => 'Attendance', 'route' => 'hrms.attendances.index', 'match' => ['hrms.attendances.*'], 'can' => 'view attendances', 'icon' => 'calendar'],
                    ['label' => 'Departments', 'route' => 'hrms.departments.index', 'match' => ['hrms.departments.*'], 'can' => 'view departments', 'icon' => 'building'],
                    ['label' => 'Designations', 'route' => 'hrms.designations.index', 'match' => ['hrms.designations.*'], 'can' => 'view designations', 'icon' => 'tag'],
                    ['label' => 'Leave Types', 'route' => 'hrms.leave-types.index', 'match' => ['hrms.leave-types.*'], 'can' => 'view leave-types', 'icon' => 'folder'],
                    ['label' => 'Leave Requests', 'route' => 'hrms.leave-requests.index', 'match' => ['hrms.leave-requests.*'], 'can' => 'view leave-requests', 'icon' => 'clipboard'],
                    ['label' => 'Payroll', 'route' => 'hrms.payrolls.index', 'match' => ['hrms.payrolls.*'], 'can' => 'view payrolls', 'icon' => 'chart-bar'],
                ],
                'Settings' => [
                    ['label' => 'Categories', 'route' => 'settings.categories.index', 'match' => ['settings.categories.*'], 'can' => 'view categories', 'icon' => 'folder'],
                    ['label' => 'Item Masters', 'route' => 'settings.item-masters.index', 'match' => ['settings.item-masters.*'], 'can' => 'view item-masters', 'icon' => 'cube'],
                    ['label' => 'WhatsApp Templates', 'route' => 'settings.whats-app-templates.index', 'match' => ['settings.whats-app-templates.*'], 'can' => 'view whats-app-templates', 'icon' => 'chat'],
                    ['label' => 'Profile', 'route' => 'profile.edit', 'match' => ['profile.*'], 'can' => null, 'icon' => 'cog'],
                ],
            ];

            $isActive = function (array $patterns): bool {
                foreach ($patterns as $pattern) {
                    if (request()->routeIs($pattern)) {
                        return true;
                    }
                }

                return false;
            };

            $userName = auth()->user()?->name ?? 'User';
            $userEmail = auth()->user()?->email ?? 'user@example.com';
            $initials = collect(explode(' ', $userName))
                ->filter()
                ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div
                x-show="sidebarOpen"
                x-transition.opacity
                class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden"
                @click="sidebarOpen = false"
                style="display: none;"
            ></div>

            <aside
                class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-800 bg-slate-950 text-slate-100 transition-transform duration-300 lg:translate-x-0"
                :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
            >
                <div class="border-b border-slate-800 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-emerald-500 text-base font-extrabold text-white shadow-lg shadow-orange-900/30">
                                FC
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-[0.24em] text-slate-400 uppercase">Factory CRM</p>
                                <p class="text-lg font-bold text-white">Operations Hub</p>
                            </div>
                        </a>
                        <button
                            type="button"
                            class="rounded-xl p-2 text-slate-400 hover:bg-slate-900 hover:text-white lg:hidden"
                            @click="sidebarOpen = false"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4 rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">System Health</p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-200">Manufacturing CRM Online</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Live
                            </span>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 space-y-8 overflow-y-auto px-4 py-6">
                    @foreach ($navigation as $group => $items)
                        <div>
                            <p class="px-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $group }}</p>
                            <div class="mt-3 space-y-1">
                                @foreach ($items as $item)
                                    @continue($item['can'] && ! auth()->user()?->can($item['can']))
                                    @php($active = $isActive($item['match']))
                                    <a
                                        href="{{ route($item['route']) }}"
                                        class="{{ $active ? 'bg-gradient-to-r from-amber-400/20 via-orange-400/10 to-emerald-400/10 text-white shadow-inner shadow-amber-500/10 ring-1 ring-amber-400/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition"
                                    >
                                        <span class="flex items-center gap-3">
                                            <span class="{{ $active ? 'bg-white/15 text-white' : 'bg-slate-800 text-slate-400' }} inline-flex h-8 w-8 items-center justify-center rounded-xl">
                                                <x-crud.icon :name="$item['icon']" class="h-4 w-4" />
                                            </span>
                                            <span>{{ $item['label'] }}</span>
                                        </span>
                                        <svg class="h-4 w-4 {{ $active ? 'text-white' : 'text-slate-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.22 14.78a.75.75 0 0 1 0-1.06L10.94 10 7.22 6.28a.75.75 0 1 1 1.06-1.06l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0Z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </aside>

            <div class="lg:pl-72">
                <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/80 backdrop-blur">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm lg:hidden"
                                @click="sidebarOpen = true"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Food Processing Factory CRM</p>
                                <div class="mt-1 text-xl font-bold text-slate-950">Operations Control Center</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden rounded-2xl border border-slate-200 bg-white px-4 py-2 text-right shadow-sm sm:block">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Shift Window</p>
                                <p class="text-sm font-semibold text-slate-900">{{ now()->format('D, d M Y') }}</p>
                            </div>

                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 text-sm font-bold text-white">
                                            {{ $initials }}
                                        </span>
                                        <span class="hidden text-left sm:block">
                                            <span class="block text-sm font-semibold text-slate-900">{{ $userName }}</span>
                                            <span class="block text-xs text-slate-500">{{ $userEmail }}</span>
                                        </span>
                                        <svg class="hidden h-4 w-4 text-slate-400 sm:block" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-3">
                                        <p class="text-sm font-semibold text-slate-900">{{ $userName }}</p>
                                        <p class="text-xs text-slate-500">{{ $userEmail }}</p>
                                    </div>
                                    <div class="border-t border-slate-100"></div>
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile Settings') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Sign Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <main class="px-4 py-6 sm:px-6 lg:px-8">
                    @isset($header)
                        <div class="mb-6 rounded-3xl border border-white/70 bg-gradient-to-r from-white to-slate-50 px-6 py-5 shadow-sm">
                            {{ $header }}
                        </div>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
