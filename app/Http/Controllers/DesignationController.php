<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $designations = Designation::with(['department', 'employees'])
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($designations);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $designation = Designation::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
        ]));

        return response()->json($designation->load('department'), 201);
    }

    public function show(Designation $designation): JsonResponse
    {
        return response()->json($designation->load(['department', 'employees']));
    }

    public function edit(Designation $designation): JsonResponse
    {
        return response()->json([
            'designation' => $designation->load('department'),
            'departments' => Department::active()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Designation $designation): JsonResponse
    {
        $designation->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
        ]));

        return response()->json($designation->fresh()->load('department'));
    }

    public function destroy(Designation $designation): JsonResponse
    {
        $designation->delete();

        return response()->json(['message' => 'Designation deleted successfully.']);
    }
}
