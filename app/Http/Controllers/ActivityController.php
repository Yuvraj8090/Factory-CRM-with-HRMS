<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\ActivityStatusLog;
use App\Models\ActivityType;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActivityController extends Controller
{
    use BuildsDataTables;

    public function index(Request $request): JsonResponse|View
    {
        $query = Activity::with(['lead', 'type', 'status', 'assignedTo', 'creator', 'statusLogs.toStatus'])
            ->when($request->filled('lead_id'), fn ($query) => $query->where('lead_id', $request->integer('lead_id')))
            ->when($request->filled('activity_status_id'), fn ($query) => $query->where('activity_status_id', $request->integer('activity_status_id')))
            ->latest();

        if ($this->isDataTableRequest($request)) {
            $statuses = ActivityStatus::active()->orderBy('name')->get(['id', 'name']);

            return $this->dataTable($query)
                ->editColumn('subject', fn (Activity $activity) => $this->recordLink(
                    $activity->subject,
                    route('crm.activities.show', $activity),
                    [Str::limit($activity->description ?: 'No description added yet.', 90)]
                ))
                ->addColumn('lead_name', fn (Activity $activity) => e($activity->lead?->name ?: 'Unknown lead'))
                ->addColumn('type_name', fn (Activity $activity) => e($activity->type?->name ?: 'Not set'))
                ->addColumn('status_dropdown', function (Activity $activity) use ($statuses) {
                    $menu = $statuses->map(function (ActivityStatus $status) use ($activity) {
                        $active = $status->id === $activity->activity_status_id;

                        return sprintf(
                            '<button type="button" class="dropdown-item%s" data-status-option data-status-id="%d" data-status-name="%s">%s%s</button>',
                            $active ? ' active' : '',
                            $status->id,
                            e($status->name),
                            $active ? '<i class="fas fa-check me-2"></i>' : '',
                            e($status->name)
                        );
                    })->implode('');

                    $lastLog = $activity->statusLogs->first();
                    $timestamp = $lastLog?->changed_at?->format('d M Y h:i A') ?: optional($activity->updated_at)->format('d M Y h:i A');

                    return sprintf(
                        '<div class="dropdown app-status-dropdown" data-status-dropdown data-update-url="%s" data-record-label="%s"><button class="btn btn-sm btn-light border dropdown-toggle d-inline-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Change current activity status">%s</button><div class="dropdown-menu shadow-sm border-0 p-2">%s</div><div class="mt-2 text-xs text-slate-500">Last changed: %s</div></div>',
                        e(route('crm.activities.update-status', $activity)),
                        e($activity->subject),
                        $this->statusBadge($activity->status?->name ?? 'Pending'),
                        $menu,
                        e($timestamp ?: 'Not available')
                    );
                })
                ->addColumn('owner_name', fn (Activity $activity) => e($activity->assignedTo?->name ?: 'Unassigned'))
                ->addColumn('due_display', fn (Activity $activity) => e(optional($activity->due_date)->format('d M Y') ?: 'No due date'))
                ->addColumn('actions', fn (Activity $activity) => $this->actionButtons(
                    route('crm.activities.show', $activity),
                    route('crm.activities.edit', $activity)
                ))
                ->rawColumns(['subject', 'status_dropdown', 'actions'])
                ->toJson();
        }

        $activities = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('activities.index', [
                'activities' => $activities,
                'leads' => Lead::query()->select('id', 'name')->orderBy('name')->get(),
                'activity_statuses' => ActivityStatus::active()->orderBy('name')->get(),
            ]);
        }

        return response()->json($activities);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'leads' => Lead::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'activity_types' => ActivityType::active()->orderBy('name')->get(),
            'activity_statuses' => ActivityStatus::active()->orderBy('name')->get(),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('activities.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => ['required', 'exists:leads,id'],
            'activity_type_id' => ['required', 'exists:activity_types,id'],
            'activity_status_id' => ['required', 'exists:activity_statuses,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        $data['created_by'] = auth()->id();

        $activity = Activity::create($data)->load(['lead', 'type', 'status', 'assignedTo', 'creator']);

        if (! $request->expectsJson()) {
            return redirect()
                ->route('crm.activities.show', $activity)
                ->with('status', 'Activity created successfully.');
        }

        return response()->json($activity, 201);
    }

    public function show(Request $request, Activity $activity): JsonResponse|View
    {
        $activity->load(['lead.stage', 'type', 'status', 'assignedTo', 'creator', 'statusLogs.fromStatus', 'statusLogs.toStatus', 'statusLogs.changedBy']);

        if (! $request->expectsJson()) {
            return view('activities.show', compact('activity'));
        }

        return response()->json($activity);
    }

    public function edit(Request $request, Activity $activity): JsonResponse|View
    {
        $payload = [
            'activity' => $activity->load(['lead', 'type', 'status', 'assignedTo']),
            'leads' => Lead::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'activity_types' => ActivityType::active()->orderBy('name')->get(),
            'activity_statuses' => ActivityStatus::active()->orderBy('name')->get(),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('activities.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, Activity $activity): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => ['required', 'exists:leads,id'],
            'activity_type_id' => ['required', 'exists:activity_types,id'],
            'activity_status_id' => ['required', 'exists:activity_statuses,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        $activity->update($data);

        if (! $request->expectsJson()) {
            return redirect()
                ->route('crm.activities.show', $activity)
                ->with('status', 'Activity updated successfully.');
        }

        return response()->json($activity->fresh()->load(['lead', 'type', 'status', 'assignedTo', 'creator']));
    }

    public function updateStatus(Request $request, Activity $activity): JsonResponse
    {
        $data = $request->validate([
            'activity_status_id' => ['required', 'exists:activity_statuses,id'],
        ]);

        $updated = DB::transaction(function () use ($activity, $data) {
            $fromStatusId = $activity->activity_status_id;
            $toStatusId = (int) $data['activity_status_id'];

            if ($fromStatusId === $toStatusId) {
                return $activity->load(['status', 'statusLogs.toStatus']);
            }

            $toStatus = ActivityStatus::findOrFail($toStatusId);

            $activity->forceFill([
                'activity_status_id' => $toStatusId,
                'completed_at' => in_array($toStatus->name, ['Completed', 'Cancelled'], true) ? now() : null,
            ])->save();

            ActivityStatusLog::create([
                'activity_id' => $activity->id,
                'from_status_id' => $fromStatusId,
                'to_status_id' => $toStatusId,
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]);

            return $activity->fresh()->load(['status', 'statusLogs.toStatus', 'statusLogs.changedBy']);
        });

        return response()->json([
            'message' => 'Activity status updated successfully.',
            'status' => $updated->status?->name ?? 'Pending',
            'status_badge' => $this->statusBadge($updated->status?->name ?? 'Pending'),
            'changed_at' => optional($updated->statusLogs->first()?->changed_at)->format('d M Y h:i A'),
        ]);
    }

    public function destroy(Request $request, Activity $activity): JsonResponse|RedirectResponse
    {
        $activity->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('crm.activities.index')
                ->with('status', 'Activity deleted successfully.');
        }

        return response()->json(['message' => 'Activity deleted successfully.']);
    }
}
