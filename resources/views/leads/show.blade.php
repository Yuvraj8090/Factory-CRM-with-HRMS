<x-app-layout>
    @php
        $stageName = optional($lead->stage)->name ?? 'Unassigned';
        $badgeClasses = [
            'New' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'Contacted' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'Qualified' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Proposal Sent' => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
            'Negotiation' => 'bg-orange-100 text-orange-800 ring-orange-200',
            'Won' => 'bg-teal-100 text-teal-800 ring-teal-200',
            'Lost' => 'bg-rose-100 text-rose-800 ring-rose-200',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Lead Record</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-950">{{ $lead->name }}</h1>
                <p class="mt-2 text-sm text-slate-600">{{ $lead->company_name ?: 'Individual Buyer' }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-outline-secondary">
                    Edit Lead
                </a>
                @if (! $lead->is_converted)
                    <form action="{{ route('crm.leads.convert', $lead) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Convert to Customer
                        </button>
                    </form>
                @else
                    <div class="inline-flex items-center justify-center rounded-2xl bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200">
                        Customer record created
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[1.25fr,0.9fr]">
        <section class="space-y-6">
            <div class="app-card app-card-body">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="{{ $badgeClasses[$stageName] ?? 'bg-slate-100 text-slate-700 ring-slate-200' }} inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset">
                                {{ $stageName }}
                            </span>
                            @if ($lead->is_converted)
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-200">
                                    Converted
                                </span>
                            @endif
                        </div>
                        <h2 class="mt-4 text-2xl font-bold text-slate-950">{{ $lead->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $lead->company_name ?: 'Individual Buyer' }}</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="app-surface px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Assigned Rep</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950">{{ optional($lead->assignedTo)->name ?: 'Unassigned' }}</p>
                        </div>
                        <div class="app-surface px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Sales Team</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950">{{ optional($lead->assignedTeam)->name ?: 'Not linked' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-2">
                    <div class="app-panel p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Email</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $lead->email ?: 'Not available' }}</p>
                    </div>
                    <div class="app-panel p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Phone</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $lead->phone ?: 'Not available' }}</p>
                    </div>
                    <div class="app-panel p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Lead Source</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $lead->lead_source ?: 'Manual entry' }}</p>
                    </div>
                    <div class="app-panel p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Created On</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ optional($lead->created_at)->format('d M Y, h:i A') ?: 'N/A' }}</p>
                    </div>
                    <div class="app-panel p-4 md:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Address</p>
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-900">
                            {{ collect([$lead->address, $lead->city, $lead->state, $lead->country, $lead->pincode])->filter()->implode(', ') ?: 'No address provided.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 app-panel bg-slate-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Internal Notes</p>
                    <p class="mt-3 text-sm leading-7 text-slate-700">{{ $lead->notes ?: 'No internal notes have been captured for this lead yet.' }}</p>
                </div>
            </div>

            <div class="app-card app-card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Activities / Notes Timeline</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-950">Follow-up history</h2>
                    </div>
                    <a href="{{ route('crm.activities.create') }}" class="app-panel bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Log Activity
                    </a>
                </div>

                <div class="mt-6 space-y-5">
                    @forelse ($lead->activities ?? [] as $activity)
                        <div class="flex gap-4">
                            <div class="mt-1 h-3 w-3 flex-none rounded-full bg-amber-400"></div>
                            <div class="flex-1 app-panel p-4">
                                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-slate-950">{{ $activity->subject }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">
                                            {{ optional($activity->type)->name ?: 'Activity' }} • {{ optional($activity->status)->name ?: 'Pending' }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        Due {{ optional($activity->due_date)->format('d M Y') ?: 'Not set' }}
                                    </p>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $activity->description ?: 'No activity notes captured.' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-white text-slate-400 shadow-sm">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-950">No activity logged yet</h3>
                            <p class="mt-2 text-sm text-slate-500">Use this timeline to capture calls, emails, meetings, and negotiation notes for the sales team.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-white/70 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 p-6 text-white shadow-lg shadow-slate-300/30">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-300">Conversion Readiness</p>
                <h2 class="mt-2 text-2xl font-bold">Sales handoff panel</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-300">Customer Fit</p>
                        <p class="mt-2 text-sm text-slate-100">{{ $lead->company_name ? 'Business buyer profile available.' : 'Direct retail contact; validate billing entity before conversion.' }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-300">Communication Data</p>
                        <p class="mt-2 text-sm text-slate-100">
                            {{ $lead->email && $lead->phone ? 'Email and phone are both available for follow-up.' : 'One or more contact channels are still missing.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="app-card app-card-body">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Quick Actions</p>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('crm.leads.index') }}" class="flex items-center justify-between app-panel px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Back to all leads
                        <span>→</span>
                    </a>
                    <a href="{{ route('crm.activities.create') }}" class="flex items-center justify-between app-panel px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Create follow-up activity
                        <span>→</span>
                    </a>
                    @if ($lead->convertedCustomer)
                        <div class="flex items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                            Customer record has been generated
                            <span>✓</span>
                        </div>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
