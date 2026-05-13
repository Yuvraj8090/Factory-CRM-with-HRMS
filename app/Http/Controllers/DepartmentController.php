<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    use BuildsDataTables;

    public function index(Request $request): JsonResponse|View
    {
        $query = Department::withCount(['designations', 'employees'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->editColumn('name', fn (Department $department) => $this->recordLink(
                    $department->name,
                    route('hrms.departments.show', $department),
                    [Str::limit($department->description ?: 'No department description added yet.', 80)]
                ))
                ->addColumn('designations_total', fn (Department $department) => e((string) $department->designations_count))
                ->addColumn('employees_total', fn (Department $department) => e((string) $department->employees_count))
                ->addColumn('status_badge', fn (Department $department) => $this->statusBadge($department->is_active ? 'Active' : 'Inactive'))
                ->addColumn('actions', fn (Department $department) => $this->actionButtons(route('hrms.departments.show', $department), route('hrms.departments.edit', $department)))
                ->rawColumns(['name', 'status_badge', 'actions'])
                ->toJson();
        }

        $departments = (clone $query)->paginate($request->integer('per_page', 15));

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
