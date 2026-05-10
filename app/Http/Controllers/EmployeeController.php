<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $employees = Employee::with(['user', 'department', 'designation'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->orderBy('employee_code')
            ->paginate($request->integer('per_page', 15));

        return response()->json($employees);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
            'designations' => Designation::query()->select('id', 'name', 'department_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $employee = Employee::create($this->validateEmployee($request));

        return response()->json($employee->load(['user', 'department', 'designation']), 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        return response()->json($employee->load(['user', 'department', 'designation']));
    }

    public function edit(Employee $employee): JsonResponse
    {
        return response()->json([
            'employee' => $employee->load(['user', 'department', 'designation']),
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
            'designations' => Designation::query()->select('id', 'name', 'department_id')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Employee $employee): JsonResponse
    {
        $employee->update($this->validateEmployee($request, $employee->id));

        return response()->json($employee->fresh()->load(['user', 'department', 'designation']));
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

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
