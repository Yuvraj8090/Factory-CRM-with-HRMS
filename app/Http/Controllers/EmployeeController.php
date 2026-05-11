<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $employees = Employee::with(['user', 'department', 'designation'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($employeeQuery) use ($search) {
                    $employeeQuery
                        ->where('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->orderBy('employee_code')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('employees.index', [
                'employees' => $employees,
                'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
            ]);
        }

        return response()->json($employees);
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = [
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
            'designations' => Designation::query()->select('id', 'name', 'department_id')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('employees.create', $data);
        }

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $employee = Employee::create($this->validateEmployee($request));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.employees.show', $employee)
                ->with('status', 'Employee record created successfully.');
        }

        return response()->json($employee->load(['user', 'department', 'designation']), 201);
    }

    public function show(Request $request, Employee $employee): JsonResponse|View
    {
        $employee->load(['user', 'department', 'designation']);

        if (! $request->expectsJson()) {
            return view('employees.show', compact('employee'));
        }

        return response()->json($employee);
    }

    public function edit(Request $request, Employee $employee): JsonResponse|View
    {
        $data = [
            'employee' => $employee->load(['user', 'department', 'designation']),
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
            'designations' => Designation::query()->select('id', 'name', 'department_id')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('employees.edit', $data);
        }

        return response()->json($data);
    }

    public function update(Request $request, Employee $employee): JsonResponse|RedirectResponse
    {
        $employee->update($this->validateEmployee($request, $employee->id));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.employees.show', $employee)
                ->with('status', 'Employee record updated successfully.');
        }

        return response()->json($employee->fresh()->load(['user', 'department', 'designation']));
    }

    public function destroy(Request $request, Employee $employee): JsonResponse|RedirectResponse
    {
        $employee->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.employees.index')
                ->with('status', 'Employee archived successfully.');
        }

        return response()->json(['message' => 'Employee deleted successfully.']);
    }

    protected function validateEmployee(Request $request, ?int $employeeId = null): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'employee_code' => ['required', 'string', 'max:255', 'unique:employees,employee_code,' . $employeeId],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'date_of_joining' => ['required', 'date'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'marital_status' => ['nullable', 'string', 'max:20'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'ifsc_code' => ['nullable', 'string', 'max:20'],
            'pf_number' => ['nullable', 'string', 'max:255'],
            'esic_number' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
