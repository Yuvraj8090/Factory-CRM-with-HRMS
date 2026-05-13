@php
    $activity = $activity ?? null;
    $isEditing = filled($activity);
@endphp

<div class="space-y-6" data-workflow="activity-form">
    <section class="app-card app-card-body">
        <div class="d-flex flex-column gap-4 border-b border-slate-200 pb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Step-by-step activity workflow</h2>
                    <p class="mt-1 text-sm text-slate-500">Each button moves the record through a clear sequence so reps know exactly what happens next.</p>
                </div>
                <div class="text-sm text-slate-500" data-workflow-status>Step 1 of 3</div>
            </div>

            <div class="app-workflow-progress" aria-hidden="true">
                <div class="app-workflow-progress-bar" data-workflow-progress style="width: 33.33%;"></div>
            </div>

            <div class="app-workflow-nav grid gap-3 lg:grid-cols-3">
                <button type="button" class="btn btn-primary" data-workflow-target="step-1">
                    <div class="fw-semibold">Step 1: Choose Lead</div>
                    <div class="small text-muted">Pick the customer opportunity and owner.</div>
                    <div class="small mt-1" data-step-state>Current</div>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-workflow-target="step-2">
                    <div class="fw-semibold">Step 2: Add Schedule</div>
                    <div class="small text-muted">Set due date and completion details.</div>
                    <div class="small mt-1" data-step-state>Next</div>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-workflow-target="step-3">
                    <div class="fw-semibold">Step 3: Review & Save</div>
                    <div class="small text-muted">Confirm the summary, then save.</div>
                    <div class="small mt-1" data-step-state>Next</div>
                </button>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div class="app-workflow-step p-4" data-workflow-step="step-1">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h3 class="h5 mb-1">Step 1: Choose lead and ownership</h3>
                        <p class="text-sm text-slate-500 mb-0">This step decides where the activity belongs and who is responsible for it.</p>
                    </div>
                    <span class="badge text-bg-primary">Required</span>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <x-input-label for="lead_id" value="Lead" />
                        <select id="lead_id" name="lead_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required title="Select the lead that this activity belongs to.">
                            <option value="">Select a lead</option>
                            @foreach ($leads as $lead)
                                <option value="{{ $lead->id }}" @selected((string) old('lead_id', $activity?->lead_id) === (string) $lead->id)>{{ $lead->name }}{{ $lead->company_name ? ' • '.$lead->company_name : '' }}</option>
                            @endforeach
                        </select>
                        <div class="app-tooltip-note mt-2">Button meaning: picking a lead connects this activity to the sales pipeline record.</div>
                        <x-input-error :messages="$errors->get('lead_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="assigned_to" value="Assigned Representative" />
                        <select id="assigned_to" name="assigned_to" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" title="Choose who should execute this activity.">
                            <option value="">Unassigned</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) old('assigned_to', $activity?->assigned_to) === (string) $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="app-tooltip-note mt-2">Button meaning: the selected rep becomes the visible owner on the activity board.</div>
                        <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="activity_type_id" value="Activity Type" />
                        <select id="activity_type_id" name="activity_type_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required title="Select the type of interaction being planned.">
                            @foreach ($activity_types as $type)
                                <option value="{{ $type->id }}" @selected((string) old('activity_type_id', $activity?->activity_type_id) === (string) $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('activity_type_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="activity_status_id" value="Activity Status" />
                        <select id="activity_status_id" name="activity_status_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required title="Set the starting status that will appear in the activity table.">
                            @foreach ($activity_statuses as $status)
                                <option value="{{ $status->id }}" @selected((string) old('activity_status_id', $activity?->activity_status_id) === (string) $status->id)>{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <div class="app-tooltip-note mt-2">Button meaning: this status controls the dropdown badge shown on the main activity table.</div>
                        <x-input-error :messages="$errors->get('activity_status_id')" class="mt-2" />
                    </div>
                    <div class="lg:col-span-2">
                        <x-input-label for="subject" value="Subject" />
                        <x-text-input id="subject" name="subject" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('subject', $activity?->subject)" required title="Add the short activity title shown in the main list." />
                        <div class="app-tooltip-note mt-2">Button meaning: the subject becomes the primary label users click from the index page.</div>
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>
                    <div class="lg:col-span-2">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" title="Use this optional note to give context for the rep.">{{ old('description', $activity?->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary" data-workflow-next title="Go to Step 2 and add timing details.">Continue to Step 2</button>
                </div>
            </div>

            <div class="app-workflow-step p-4 d-none" data-workflow-step="step-2" data-workflow-optional="true">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h3 class="h5 mb-1">Step 2: Add schedule details</h3>
                        <p class="text-sm text-slate-500 mb-0">This is optional. Use it when the activity needs a due date or already has a completion timestamp.</p>
                    </div>
                    <span class="badge text-bg-secondary">Optional</span>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <x-input-label for="due_date" value="Due Date" />
                        <x-text-input id="due_date" name="due_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('due_date', optional($activity?->due_date)->format('Y-m-d'))" title="Set when the rep should complete the task." />
                        <div class="app-tooltip-note mt-2">Button meaning: the due date appears in the activity table and helps with prioritization.</div>
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="completed_at" value="Completed At" />
                        <x-text-input id="completed_at" name="completed_at" type="datetime-local" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('completed_at', optional($activity?->completed_at)->format('Y-m-d\TH:i'))" title="Set this only if the work is already finished." />
                        <div class="app-tooltip-note mt-2">Button meaning: this marks when the work actually finished for audit history.</div>
                        <x-input-error :messages="$errors->get('completed_at')" class="mt-2" />
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
                    <div>
                        <button type="button" class="btn btn-outline-secondary" data-workflow-prev title="Go back to Step 1 without losing your saved draft.">Back to Step 1</button>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-workflow-skip title="Skip optional schedule details and move to the final review.">Skip This Optional Step</button>
                        <button type="button" class="btn btn-primary" data-workflow-next title="Continue to the final review step.">Continue to Step 3</button>
                    </div>
                </div>
            </div>

            <div class="app-workflow-step p-4 d-none" data-workflow-step="step-3">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h3 class="h5 mb-1">Step 3: Review and save</h3>
                        <p class="text-sm text-slate-500 mb-0">Use the final action button below to {{ $isEditing ? 'update' : 'save' }} the activity.</p>
                    </div>
                    <span class="badge text-bg-success">Final step</span>
                </div>

                <div class="app-surface p-4">
                    <div class="grid gap-3 lg:grid-cols-2">
                        <div>
                            <div class="text-xs uppercase text-muted">Primary action</div>
                            <div class="fw-semibold mt-1">{{ $isEditing ? 'Update Activity' : 'Save Activity' }}</div>
                            <div class="app-tooltip-note mt-1">Button meaning: submits the form to Laravel, validates the data on the server, and redirects to the activity details page.</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-muted">Draft persistence</div>
                            <div class="fw-semibold mt-1">Saved in local storage until submit</div>
                            <div class="app-tooltip-note mt-1">Refresh-safe draft recovery is enabled across the workflow so partially entered information stays available.</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between gap-3 mt-4 flex-column flex-sm-row">
                    <button type="button" class="btn btn-outline-secondary" data-workflow-prev title="Return to the previous step for adjustments.">Back to Step 2</button>
                    <button type="submit" class="btn btn-primary" title="{{ $isEditing ? 'Save the latest changes to this activity.' : 'Create the new activity and move to the details page.' }}">
                        {{ $isEditing ? 'Update Activity' : 'Save Activity' }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('crm.activities.index') }}" class="btn btn-outline-secondary">
            Cancel
        </a>
    </div>
</div>
