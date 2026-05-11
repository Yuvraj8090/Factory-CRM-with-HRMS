<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignationController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $designations = Designation::with(['department', 'employees'])
            ->withCount('employees')
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('designations.index', compact('designations'));
        }

        return response()->json($designations);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('designations.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $designation = Designation::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.designations.show', $designation)
                ->with('status', 'Designation created successfully.');
        }

        return response()->json($designation->load('department'), 201);
    }

    public function show(Request $request, Designation $designation): JsonResponse|View
    {
        $designation->load(['department', 'employees.user']);

        if (! $request->expectsJson()) {
            return view('designations.show', compact('designation'));
        }

        return response()->json($designation);
    }

    public function edit(Request $request, Designation $designation): JsonResponse|View
    {
        $payload = [
            'designation' => $designation->load('department'),
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('designations.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, Designation $designation): JsonResponse|RedirectResponse
    {
        $designation->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.designations.show', $designation)
                ->with('status', 'Designation updated successfully.');
        }

        return response()->json($designation->fresh()->load('department'));
    }

    public function destroy(Request $request, Designation $designation): JsonResponse|RedirectResponse
    {
        $designation->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.designations.index')
                ->with('status', 'Designation deleted successfully.');
        }

        return response()->json(['message' => 'Designation deleted successfully.']);
    }
}
