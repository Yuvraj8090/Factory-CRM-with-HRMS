<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            :title="$activity->subject"
            description="Review the full execution context for this activity, including customer linkage, ownership, status, and timing."
            icon="clipboard"
            :breadcrumbs="[['label' => 'CRM'], ['label' => 'Activities', 'url' => route('crm.activities.index')], ['label' => 'Details']]"
            :back-url="route('crm.activities.index')"
            back-label="Back to Activities"
            :action-url="route('crm.activities.edit', $activity)"
            action-label="Edit Activity"
        />
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_380px]">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Lead</p>
                    <p class="mt-2 text-base font-semibold text-slate-950">{{ $activity->lead?->name ?: 'Unknown lead' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Assigned Representative</p>
                    <p class="mt-2 text-base font-semibold text-slate-950">{{ $activity->assignedTo?->name ?: 'Unassigned' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Type</p>
                    <p class="mt-2 text-base font-semibold text-slate-950">{{ $activity->type?->name ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                    <div class="mt-2"><x-crud.status-badge :value="$activity->status?->name ?? 'Pending'" /></div>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Due Date</p>
                    <p class="mt-2 text-base font-semibold text-slate-950">{{ optional($activity->due_date)->format('d M Y') ?: 'No due date scheduled' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Completed At</p>
                    <p class="mt-2 text-base font-semibold text-slate-950">{{ optional($activity->completed_at)->format('d M Y, h:i A') ?: 'Still open' }}</p>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p>
                <p class="mt-3 text-sm leading-7 text-slate-700">{{ $activity->description ?: 'No supporting description has been captured for this activity yet.' }}</p>
            </div>
        </section>

        <section class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-emerald-50 p-6 shadow-sm shadow-slate-200/60">
            <h2 class="text-lg font-bold text-slate-950">Execution Summary</h2>
            <p class="mt-2 text-sm leading-7 text-slate-700">Use this panel to quickly understand whether the action is on track, overdue, completed, or ready for reassignment during pipeline reviews.</p>
            <dl class="mt-6 space-y-4 text-sm text-slate-700">
                <div class="flex items-center justify-between">
                    <dt>Created by</dt>
                    <dd class="font-semibold text-slate-950">{{ $activity->creator?->name ?: 'System user unavailable' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt>Linked lead stage</dt>
                    <dd class="font-semibold text-slate-950">{{ $activity->lead?->stage?->name ?: 'Not available' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt>Activity age</dt>
                    <dd class="font-semibold text-slate-950">{{ $activity->created_at?->diffForHumans() }}</dd>
                </div>
            </dl>
        </section>
    </div>
</x-app-layout>
