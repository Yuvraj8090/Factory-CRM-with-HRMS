@php
    $activity = $activity ?? null;
@endphp

<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Activity Details</h2>
            <p class="mt-1 text-sm text-slate-500">Schedule the next customer-facing action with a clear owner, status, and timing so the sales team can follow through confidently.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <x-input-label for="lead_id" value="Lead" />
                <select id="lead_id" name="lead_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    <option value="">Select a lead</option>
                    @foreach ($leads as $lead)
                        <option value="{{ $lead->id }}" @selected((string) old('lead_id', $activity?->lead_id) === (string) $lead->id)>{{ $lead->name }}{{ $lead->company_name ? ' • '.$lead->company_name : '' }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('lead_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="assigned_to" value="Assigned Representative" />
                <select id="assigned_to" name="assigned_to" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">Unassigned</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected((string) old('assigned_to', $activity?->assigned_to) === (string) $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="activity_type_id" value="Activity Type" />
                <select id="activity_type_id" name="activity_type_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    @foreach ($activity_types as $type)
                        <option value="{{ $type->id }}" @selected((string) old('activity_type_id', $activity?->activity_type_id) === (string) $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('activity_type_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="activity_status_id" value="Activity Status" />
                <select id="activity_status_id" name="activity_status_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    @foreach ($activity_statuses as $status)
                        <option value="{{ $status->id }}" @selected((string) old('activity_status_id', $activity?->activity_status_id) === (string) $status->id)>{{ $status->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('activity_status_id')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="subject" value="Subject" />
                <x-text-input id="subject" name="subject" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('subject', $activity?->subject)" required />
                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="due_date" value="Due Date" />
                <x-text-input id="due_date" name="due_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('due_date', optional($activity?->due_date)->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="completed_at" value="Completed At" />
                <x-text-input id="completed_at" name="completed_at" type="datetime-local" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('completed_at', optional($activity?->completed_at)->format('Y-m-d\TH:i'))" />
                <x-input-error :messages="$errors->get('completed_at')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('description', $activity?->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('crm.activities.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
            {{ $activity ? 'Update Activity' : 'Save Activity' }}
        </button>
    </div>
</div>
