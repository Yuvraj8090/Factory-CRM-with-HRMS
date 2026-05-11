<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\ActivityType;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $activities = Activity::with(['lead', 'type', 'status', 'assignedTo', 'creator'])
            ->when($request->filled('lead_id'), fn ($query) => $query->where('lead_id', $request->integer('lead_id')))
            ->when($request->filled('activity_status_id'), fn ($query) => $query->where('activity_status_id', $request->integer('activity_status_id')))
            ->latest()
            ->paginate($request->integer('per_page', 15));

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
        $activity->load(['lead.stage', 'type', 'status', 'assignedTo', 'creator']);

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
