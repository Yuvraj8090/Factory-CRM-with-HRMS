<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $departments = Department::withCount(['designations', 'employees'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($departments);
    }

    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Department form metadata ready.']);
    }

    public function store(Request $request): JsonResponse
    {
        $department = Department::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($department, 201);
    }

    public function show(Department $department): JsonResponse
    {
        return response()->json($department->load(['designations', 'employees']));
    }

    public function edit(Department $department): JsonResponse
    {
        return response()->json($department);
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $department->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($department->fresh());
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully.']);
    }
}
