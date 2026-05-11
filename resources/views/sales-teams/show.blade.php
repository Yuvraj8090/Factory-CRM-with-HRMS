@php($team = $salesTeam ?? $sales_team)
<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$team->name" description="Review the sales pod structure, leadership assignment, and work distribution for this team." icon="users" :breadcrumbs="[['label' => 'CRM'], ['label' => 'Sales Teams', 'url' => route('crm.sales-teams.index')], ['label' => 'Details']]" :back-url="route('crm.sales-teams.index')" back-label="Back to Sales Teams" :action-url="route('crm.sales-teams.edit', $team)" action-label="Edit Team" />
    </x-slot>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_380px]">
        <section class="app-card app-card-body">
            <div class="grid gap-6 md:grid-cols-2">
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Team Lead</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $team->teamLead?->name ?: 'Not assigned' }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$team->is_active ? 'Active' : 'Inactive'" /></div></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Member Count</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $team->members?->count() ?? 0 }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Assigned Leads</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $team->leads?->count() ?? 0 }}</p></div>
            </div>
            <div class="mt-8 border-t border-slate-200 pt-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p>
                <p class="mt-3 text-sm leading-7 text-slate-700">{{ $team->description ?: 'No operating description has been documented for this sales team yet.' }}</p>
            </div>
        </section>
        <section class="app-card app-card-body">
            <h2 class="text-lg font-bold text-slate-950">Team Roster</h2>
            <p class="mt-2 text-sm leading-7 text-slate-700">People attached to this team can be used for lead allocation, collaboration, and performance reviews.</p>
            <div class="mt-6 space-y-3">
                @forelse ($team->members ?? [] as $member)
                    <div class="app-panel px-4 py-3">
                        <p class="font-semibold text-slate-950">{{ $member->name }}</p>
                        <p class="text-sm text-slate-500">{{ $member->email ?: 'No email on file' }}</p>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No representatives are currently mapped to this team.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
