<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Executive Snapshot</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-950">Factory Performance Dashboard</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-600">
                    A live operating view of pipeline, receivables, workforce attendance, and production-facing customer health.
                </p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <span class="font-semibold">Today:</span> {{ now()->format('l, d M Y') }}
            </div>
        </div>
    </x-slot>

    @php
        $activeLeads = \App\Models\Lead::query()->where('is_converted', false)->count();
        $pendingInvoices = \App\Models\Invoice::query()->whereIn('payment_status', ['Pending', 'Partial'])->count();
        $presentEmployees = \App\Models\Attendance::query()->whereDate('date', today())->where('status', 'Present')->count();
        $monthlyCollections = \App\Models\Payment::query()->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount');

        $cards = [
            [
                'label' => 'Total Active Leads',
                'value' => number_format($activeLeads),
                'trend' => 'Open opportunities being actively worked by the sales team.',
                'tone' => 'amber',
            ],
            [
                'label' => 'Pending Invoices',
                'value' => number_format($pendingInvoices),
                'trend' => 'Invoices still awaiting full settlement.',
                'tone' => 'rose',
            ],
            [
                'label' => 'Employees Present Today',
                'value' => number_format($presentEmployees),
                'trend' => 'Attendance marked present for the current shift.',
                'tone' => 'emerald',
            ],
            [
                'label' => 'Collections This Month',
                'value' => '₹' . number_format((float) $monthlyCollections, 2),
                'trend' => 'Cash inflow captured against active receivables.',
                'tone' => 'sky',
            ],
        ];

        $tones = [
            'amber' => 'from-amber-400 via-orange-400 to-amber-500 text-amber-950 shadow-orange-200/70',
            'rose' => 'from-rose-400 via-red-400 to-rose-500 text-rose-950 shadow-rose-200/70',
            'emerald' => 'from-emerald-400 via-teal-400 to-emerald-500 text-emerald-950 shadow-emerald-200/70',
            'sky' => 'from-sky-400 via-cyan-400 to-sky-500 text-sky-950 shadow-sky-200/70',
        ];
    @endphp

    <div class="space-y-6">
        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($cards as $card)
                <article class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
                    <div class="h-2 bg-gradient-to-r {{ $tones[$card['tone']] }}"></div>
                    <div class="p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-950">{{ $card['value'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $card['trend'] }}</p>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.4fr,0.9fr]">
            <div class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Commercial Pulse</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-950">Revenue and pipeline readiness</h2>
                    </div>
                    <a href="{{ route('crm.leads.index') }}" class="rounded-2xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Open Leads
                    </a>
                </div>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Qualified Funnel</p>
                        <p class="mt-3 text-2xl font-bold text-slate-950">{{ \App\Models\Lead::query()->whereHas('stage', fn ($query) => $query->where('name', 'Qualified'))->count() }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Sent Quotations</p>
                        <p class="mt-3 text-2xl font-bold text-slate-950">{{ \App\Models\Quotation::query()->where('status', 'Sent')->count() }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Overdue Risk</p>
                        <p class="mt-3 text-2xl font-bold text-slate-950">{{ \App\Models\Invoice::query()->where('invoice_status', 'Overdue')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/70 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 p-6 text-white shadow-lg shadow-slate-300/30">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-300">Plant Readiness</p>
                <h2 class="mt-2 text-2xl font-bold">Today’s operating posture</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-300">Inventory Watch</p>
                        <p class="mt-2 text-sm text-slate-100">
                            {{ \App\Models\ItemMaster::query()->whereColumn('opening_stock', '<=', 'reorder_level')->count() }} items are at or below reorder threshold.
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-300">Workforce Exceptions</p>
                        <p class="mt-2 text-sm text-slate-100">
                            {{ \App\Models\Attendance::query()->whereDate('date', today())->whereIn('status', ['Late', 'Half Day', 'Leave'])->count() }} attendance records need managerial attention.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
