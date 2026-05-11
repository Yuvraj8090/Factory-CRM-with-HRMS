<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Sales Teams" description="Organize sellers into accountable pods with a clear team lead, active roster, and lead-sharing structure." icon="users" :breadcrumbs="[['label' => 'CRM'], ['label' => 'Sales Teams']]" :action-url="route('crm.sales-teams.create')" action-label="Add Sales Team" />
    </x-slot>

    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Team</th>
                        <th class="px-6 py-4">Team Lead</th>
                        <th class="px-6 py-4">Members</th>
                        <th class="px-6 py-4">Open Leads</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($teams as $team)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5">
                                <a href="{{ route('crm.sales-teams.show', $team) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $team->name }}</a>
                                <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($team->description, 90) ?: 'No team description added yet.' }}</p>
                            </td>
                            <td class="px-6 py-5 text-slate-600">{{ $team->teamLead?->name ?: 'Not assigned' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $team->members_count ?? $team->members?->count() ?? 0 }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $team->leads_count ?? $team->leads?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$team->is_active ? 'Active' : 'Inactive'" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('crm.sales-teams.show', $team) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('crm.sales-teams.edit', $team) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No sales teams are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $teams->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
