<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveTypeController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $leaveTypes = LeaveType::withCount('leaveRequests')
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('leave-types.index', compact('leaveTypes'));
        }

        return response()->json($leaveTypes);
    }

    public function create(Request $request): JsonResponse|View
    {
        if (! $request->expectsJson()) {
            return view('leave-types.create');
        }

        return response()->json(['message' => 'Leave type form metadata ready.']);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $leaveType = LeaveType::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
            'leave_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.leave-types.show', $leaveType)
                ->with('status', 'Leave type created successfully.');
        }

        return response()->json($leaveType, 201);
    }

    public function show(Request $request, LeaveType $leaveType): JsonResponse|View
    {
        $leaveType->load('leaveRequests');

        if (! $request->expectsJson()) {
            return view('leave-types.show', compact('leaveType'));
        }

        return response()->json($leaveType);
    }

    public function edit(Request $request, LeaveType $leaveType): JsonResponse|View
    {
        if (! $request->expectsJson()) {
            return view('leave-types.edit', compact('leaveType'));
        }

        return response()->json($leaveType);
    }

    public function update(Request $request, LeaveType $leaveType): JsonResponse|RedirectResponse
    {
        $leaveType->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name,' . $leaveType->id],
            'leave_days_per_year' => ['required', 'integer', 'min:0'],
            'is_paid' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.leave-types.show', $leaveType)
                ->with('status', 'Leave type updated successfully.');
        }

        return response()->json($leaveType->fresh());
    }

    public function destroy(Request $request, LeaveType $leaveType): JsonResponse|RedirectResponse
    {
        $leaveType->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.leave-types.index')
                ->with('status', 'Leave type deleted successfully.');
        }

        return response()->json(['message' => 'Leave type deleted successfully.']);
    }
}
