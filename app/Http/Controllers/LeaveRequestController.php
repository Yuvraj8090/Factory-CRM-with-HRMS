<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    use BuildsDataTables;

    public function __construct()
    {
        $this->authorizeResource(LeaveRequest::class, 'leave_request');
    }

    public function index(Request $request): JsonResponse|View
    {
        $query = LeaveRequest::with(['user', 'leaveType', 'approver'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->whereHas('user', fn ($userQuery) => $userQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('start_date');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->addColumn('employee_name', fn (LeaveRequest $leaveRequest) => $this->recordLink(
                    $leaveRequest->user?->name ?? 'Unknown user',
                    route('hrms.leave-requests.show', $leaveRequest)
                ))
                ->addColumn('leave_type_name', fn (LeaveRequest $leaveRequest) => e($leaveRequest->leaveType?->name ?: 'Not set'))
                ->addColumn('dates_display', fn (LeaveRequest $leaveRequest) => e(optional($leaveRequest->start_date)->format('d M Y') . ' to ' . optional($leaveRequest->end_date)->format('d M Y')))
                ->addColumn('days_total', fn (LeaveRequest $leaveRequest) => e(number_format((float) $leaveRequest->total_days, 2)))
                ->addColumn('status_badge', fn (LeaveRequest $leaveRequest) => $this->statusBadge($leaveRequest->status))
                ->addColumn('actions', fn (LeaveRequest $leaveRequest) => $this->actionButtons(route('hrms.leave-requests.show', $leaveRequest), route('hrms.leave-requests.edit', $leaveRequest)))
                ->rawColumns(['employee_name', 'status_badge', 'actions'])
                ->toJson();
        }

        $leaveRequests = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('leave-requests.index', [
                'leaveRequests' => $leaveRequests,
                'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
                'leaveTypes' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
                'statuses' => ['Pending', 'Approved', 'Rejected'],
            ]);
        }

        return response()->json($leaveRequests);
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = [
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'leaveTypes' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
            'leave_types' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
            'statuses' => ['Pending', 'Approved', 'Rejected'],
        ];

        if (! $request->expectsJson()) {
            return view('leave-requests.create', $data);
        }

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validateLeaveRequest($request);
        $data['total_days'] = $this->calculateDays($data['start_date'], $data['end_date']);

        $leaveRequest = LeaveRequest::create($data)->load(['user', 'leaveType', 'approver']);

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.leave-requests.show', $leaveRequest)
                ->with('status', 'Leave request created successfully.');
        }

        return response()->json($leaveRequest, 201);
    }

    public function show(Request $request, LeaveRequest $leaveRequest): JsonResponse|View
    {
        $leaveRequest->load(['user', 'leaveType', 'approver']);

        if (! $request->expectsJson()) {
            return view('leave-requests.show', compact('leaveRequest'));
        }

        return response()->json($leaveRequest);
    }

    public function edit(Request $request, LeaveRequest $leaveRequest): JsonResponse|View
    {
        $data = [
            'leave_request' => $leaveRequest->load(['user', 'leaveType', 'approver']),
            'leaveRequest' => $leaveRequest->load(['user', 'leaveType', 'approver']),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'leaveTypes' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
            'leave_types' => LeaveType::query()->select('id', 'name')->orderBy('name')->get(),
            'statuses' => ['Pending', 'Approved', 'Rejected'],
        ];

        if (! $request->expectsJson()) {
            return view('leave-requests.edit', $data);
        }

        return response()->json($data);
    }

    public function update(Request $request, LeaveRequest $leaveRequest): JsonResponse|RedirectResponse
    {
        $data = $this->validateLeaveRequest($request);
        $data['total_days'] = $this->calculateDays($data['start_date'], $data['end_date']);
        $leaveRequest->update($data);

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.leave-requests.show', $leaveRequest)
                ->with('status', 'Leave request updated successfully.');
        }

        return response()->json($leaveRequest->fresh()->load(['user', 'leaveType', 'approver']));
    }

    public function destroy(Request $request, LeaveRequest $leaveRequest): JsonResponse|RedirectResponse
    {
        $leaveRequest->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.leave-requests.index')
                ->with('status', 'Leave request deleted successfully.');
        }

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
