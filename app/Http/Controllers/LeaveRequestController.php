<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $leaveRequests = LeaveRequest::with(['user', 'leaveType', 'approver'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('start_date')
            ->paginate($request->integer('per_page', 15));

        return response()->json($leaveRequests);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'leave_types' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateLeaveRequest($request);
        $data['total_days'] = $this->calculateDays($data['start_date'], $data['end_date']);

        $leaveRequest = LeaveRequest::create($data)->load(['user', 'leaveType', 'approver']);

        return response()->json($leaveRequest, 201);
    }

    public function show(LeaveRequest $leaveRequest): JsonResponse
    {
        return response()->json($leaveRequest->load(['user', 'leaveType', 'approver']));
    }

    public function edit(LeaveRequest $leaveRequest): JsonResponse
    {
        return response()->json([
            'leave_request' => $leaveRequest->load(['user', 'leaveType', 'approver']),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'leave_types' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        $data = $this->validateLeaveRequest($request);
        $data['total_days'] = $this->calculateDays($data['start_date'], $data['end_date']);
        $leaveRequest->update($data);

        return response()->json($leaveRequest->fresh()->load(['user', 'leaveType', 'approver']));
    }

    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        $leaveRequest->delete();

        return response()->json(['message' => 'Leave request deleted successfully.']);
    }

    protected function validateLeaveRequest(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string'],
            'status' => ['required', 'in:Pending,Approved,Rejected'],
            'approved_by' => ['nullable', 'exists:users,id'],
        ]);
    }

    protected function calculateDays(string $startDate, string $endDate): float
    {
        return Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
    }
}
