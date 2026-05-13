<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Activities"
            description="Track every follow-up, meeting, call, and handoff so no live opportunity disappears between touchpoints."
            icon="clipboard"
            :breadcrumbs="[['label' => 'CRM'], ['label' => 'Activities']]"
            :action-url="route('crm.activities.create')"
            action-label="Add Activity"
        />
    </x-slot>

    <section class="app-card">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="d-flex flex-column flex-lg-row gap-4 justify-content-between align-items-lg-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">3-step execution workflow</h2>
                    <p class="mt-1 text-sm text-slate-500">Step 1 selects the lead, Step 2 captures timing and ownership, and Step 3 confirms the activity. Optional schedule details can be skipped in the form.</p>
                </div>
                <div class="app-tooltip-note">
                    Status buttons open a dropdown so reps can move work between Pending, In Progress, Completed, and other active states without leaving this page.
                </div>
            </div>
        </div>

        <form id="activities-filters" method="GET" data-local-storage-form="activities.filters" data-local-storage-clear-on-submit="false" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]">
            <div>
                <x-input-label for="lead_id" value="Lead" />
                <select id="lead_id" name="lead_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">All leads</option>
                    @foreach (($leads ?? collect()) as $lead)
                        <option value="{{ $lead->id }}" @selected((string) request('lead_id') === (string) $lead->id)>{{ $lead->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="activity_status_id" value="Status" />
                <select id="activity_status_id" name="activity_status_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">All statuses</option>
                    @foreach (($activity_statuses ?? collect()) as $status)
                        <option value="{{ $status->id }}" @selected((string) request('activity_status_id') === (string) $status->id)>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table
                id="activities-table"
                class="table table-hover app-data-table text-sm"
                data-datatable-url="{{ route('crm.activities.index') }}"
                data-datatable-filter-form="#activities-filters"
                data-datatable-storage-key="activities"
                data-datatable-columns='{{ \Illuminate\Support\Js::from([
                    ["data" => "subject", "name" => "subject"],
                    ["data" => "lead_name", "name" => "lead.name", "searchable" => false],
                    ["data" => "type_name", "name" => "type.name", "searchable" => false],
                    ["data" => "status_dropdown", "name" => "status.name", "orderable" => false, "searchable" => false],
                    ["data" => "owner_name", "name" => "assignedTo.name", "searchable" => false],
                    ["data" => "due_display", "name" => "due_date"],
                    ["data" => "actions", "name" => "id", "orderable" => false, "searchable" => false],
                ]) }}'
            >
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Lead</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Owner</th>
                        <th class="px-6 py-4">Due</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($activities as $activity)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5">
                                <a href="{{ route('crm.activities.show', $activity) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $activity->subject }}</a>
                                <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($activity->description, 90) ?: 'No description added yet.' }}</p>
                            </td>
                            <td class="px-6 py-5 text-slate-600">{{ $activity->lead?->name ?: 'Unknown lead' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $activity->type?->name ?: 'Not set' }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$activity->status?->name ?? 'Pending'" /></td>
                            <td class="px-6 py-5 text-slate-600">{{ $activity->assignedTo?->name ?: 'Unassigned' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($activity->due_date)->format('d M Y') ?: 'No due date' }}</td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('crm.activities.show', $activity) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                    <a href="{{ route('crm.activities.edit', $activity) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-sm text-slate-500">No activities have been logged for the selected filters yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>
            {{ $activities->withQueryString()->links() }}
        </div>
    </section>
</x-app-layout>
