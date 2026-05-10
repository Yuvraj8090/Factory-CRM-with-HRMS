<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            LeaveType::withCount('leaveRequests')
                ->orderBy('name')
                ->paginate($request->integer('per_page', 15))
        );
    }

    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Leave type form metadata ready.']);
    }

    public function store(Request $request): JsonResponse
    {
        $leaveType = LeaveType::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
            'leave_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['required', 'boolean'],
        ]));

        return response()->json($leaveType, 201);
    }

    public function show(LeaveType $leaveType): JsonResponse
    {
        return response()->json($leaveType->load('leaveRequests'));
    }

    public function edit(LeaveType $leaveType): JsonResponse
    {
        return response()->json($leaveType);
    }

    public function update(Request $request, LeaveType $leaveType): JsonResponse
    {
        $leaveType->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name,' . $leaveType->id],
            'leave_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['required', 'boolean'],
        ]));

        return response()->json($leaveType->fresh());
    }

    public function destroy(LeaveType $leaveType): JsonResponse
    {
        $leaveType->delete();

        return response()->json(['message' => 'Leave type deleted successfully.']);
    }
}
