<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\ActivityType;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $activities = Activity::with(['lead', 'type', 'status', 'assignedTo', 'creator'])
            ->when($request->filled('lead_id'), fn ($query) => $query->where('lead_id', $request->integer('lead_id')))
            ->when($request->filled('activity_status_id'), fn ($query) => $query->where('activity_status_id', $request->integer('activity_status_id')))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($activities);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'leads' => Lead::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'activity_types' => ActivityType::active()->orderBy('name')->get(),
            'activity_statuses' => ActivityStatus::active()->orderBy('name')->get(),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
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

        return response()->json($activity, 201);
    }

    public function show(Activity $activity): JsonResponse
    {
        return response()->json($activity->load(['lead', 'type', 'status', 'assignedTo', 'creator']));
    }

    public function edit(Activity $activity): JsonResponse
    {
        return response()->json([
            'activity' => $activity->load(['lead', 'type', 'status', 'assignedTo']),
            'leads' => Lead::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'activity_types' => ActivityType::active()->orderBy('name')->get(),
            'activity_statuses' => ActivityStatus::active()->orderBy('name')->get(),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Activity $activity): JsonResponse
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

        return response()->json($activity->fresh()->load(['lead', 'type', 'status', 'assignedTo', 'creator']));
    }

    public function destroy(Activity $activity): JsonResponse
    {
        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully.']);
    }
}
