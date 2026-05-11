<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $departments = Department::withCount(['designations', 'employees'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('departments.index', compact('departments'));
        }

        return response()->json($departments);
    }

    public function create(Request $request): JsonResponse|View
    {
        if (! $request->expectsJson()) {
            return view('departments.create');
        }

        return response()->json(['message' => 'Department form metadata ready.']);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $department = Department::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.departments.show', $department)
                ->with('status', 'Department created successfully.');
        }

        return response()->json($department, 201);
    }

    public function show(Request $request, Department $department): JsonResponse|View
    {
        $department->load(['designations', 'employees']);

        if (! $request->expectsJson()) {
            return view('departments.show', compact('department'));
        }

        return response()->json($department);
    }

    public function edit(Request $request, Department $department): JsonResponse|View
    {
        if (! $request->expectsJson()) {
            return view('departments.edit', compact('department'));
        }

        return response()->json($department);
    }

    public function update(Request $request, Department $department): JsonResponse|RedirectResponse
    {
        $department->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.departments.show', $department)
                ->with('status', 'Department updated successfully.');
        }

        return response()->json($department->fresh());
    }

    public function destroy(Request $request, Department $department): JsonResponse|RedirectResponse
    {
        $department->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.departments.index')
                ->with('status', 'Department deleted successfully.');
        }

        return response()->json(['message' => 'Department deleted successfully.']);
    }
}
