<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Lead Management"
            description="Monitor every prospect, ownership assignment, and stage movement so the pipeline stays visible and commercially actionable."
            icon="user-plus"
            :breadcrumbs="[['label' => 'CRM'], ['label' => 'Leads']]"
            :action-url="route('crm.leads.create')"
            action-label="Add New Lead"
        />
    </x-slot>

    @php
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

    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Lead Pipeline Register</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ method_exists($leads, 'total') ? $leads->total() : count($leads) }} lead records currently available.
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Open Leads</p>
                        <p class="mt-2 text-xl font-bold text-slate-950">{{ $leads->where('is_converted', false)->count() }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Qualified</p>
                        <p class="mt-2 text-xl font-bold text-slate-950">{{ $leads->filter(fn ($lead) => optional($lead->stage)->name === 'Qualified')->count() }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Converted</p>
                        <p class="mt-2 text-xl font-bold text-slate-950">{{ $leads->where('is_converted', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Lead</th>
                            <th class="px-6 py-4">Stage</th>
                            <th class="px-6 py-4">Assigned Rep</th>
                            <th class="px-6 py-4">Source</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($leads as $lead)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-sm font-bold text-white">
                                            {{ strtoupper(substr($lead->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('crm.leads.show', $lead) }}" class="text-sm font-bold text-slate-950 hover:text-amber-700">
                                                {{ $lead->name }}
                                            </a>
                                            <p class="mt-1 text-sm text-slate-500">{{ $lead->company_name ?: 'Individual Buyer' }}</p>
                                            <p class="mt-2 text-xs text-slate-500">{{ $lead->email ?: 'No email' }}{{ $lead->phone ? ' • ' . $lead->phone : '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    @php($stageName = optional($lead->stage)->name ?? 'Unassigned')
                                    <span class="{{ $badgeClasses[$stageName] ?? 'bg-slate-100 text-slate-700 ring-slate-200' }} inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset">
                                        {{ $stageName }}
                                    </span>
                                    @if ($lead->is_converted)
                                        <div class="mt-2 text-xs font-semibold text-emerald-700">Converted to customer</div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-slate-600">
                                    <p class="font-semibold text-slate-900">{{ optional($lead->assignedTo)->name ?: 'Unassigned' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ optional($lead->assignedTeam)->name ?: 'No team linked' }}</p>
                                </td>
                                <td class="px-6 py-5 align-top text-slate-600">
                                    {{ $lead->lead_source ?: 'Manual' }}
                                </td>
                                <td class="px-6 py-5 align-top text-slate-600">
                                    {{ collect([$lead->city, $lead->state, $lead->country])->filter()->implode(', ') ?: 'Not provided' }}
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('crm.leads.show', $lead) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                                            View
                                        </a>
                                        <a href="{{ route('crm.leads.edit', $lead) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-slate-100 text-slate-500">
                                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 text-lg font-bold text-slate-950">No leads available yet</h3>
                                        <p class="mt-2 text-sm text-slate-500">Start by adding your first enquiry so the sales team can begin pipeline tracking.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($leads, 'links'))
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $leads->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
