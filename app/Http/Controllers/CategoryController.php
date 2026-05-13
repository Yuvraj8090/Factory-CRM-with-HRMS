<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use BuildsDataTables;

    public function index(Request $request): JsonResponse|View
    {
        $query = Category::with(['parent', 'children', 'items'])
            ->withCount('items')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->editColumn('name', fn (Category $category) => $this->recordLink(
                    $category->name,
                    route('settings.categories.show', $category),
                    [Str::limit($category->description ?: 'No category description added yet.', 80)]
                ))
                ->addColumn('parent_name', fn (Category $category) => e($category->parent?->name ?: 'Top-level'))
                ->addColumn('items_total', fn (Category $category) => e((string) ($category->items_count ?? 0)))
                ->addColumn('status_badge', fn (Category $category) => $this->statusBadge($category->is_active ? 'Active' : 'Inactive'))
                ->addColumn('actions', fn (Category $category) => $this->actionButtons(route('settings.categories.show', $category), route('settings.categories.edit', $category)))
                ->rawColumns(['name', 'status_badge', 'actions'])
                ->toJson();
        }

        $categories = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('categories.index', compact('categories'));
        }

        return response()->json($categories);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'parent_categories' => Category::query()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('categories.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $category = Category::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.categories.show', $category)
                ->with('status', 'Category created successfully.');
        }

        return response()->json($category->load('parent'), 201);
    }

    public function show(Request $request, Category $category): JsonResponse|View
    {
        $category->load(['parent', 'children', 'items']);

        if (! $request->expectsJson()) {
            return view('categories.show', compact('category'));
        }

        return response()->json($category);
    }

    public function edit(Request $request, Category $category): JsonResponse|View
    {
        $payload = [
            'category' => $category->load('parent'),
            'parent_categories' => Category::whereKeyNot($category->id)->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('categories.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, Category $category): JsonResponse|RedirectResponse
    {
        $category->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:' . $category->id],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.categories.show', $category)
                ->with('status', 'Category updated successfully.');
        }

        return response()->json($category->fresh()->load('parent'));
    }

    public function destroy(Request $request, Category $category): JsonResponse|RedirectResponse
    {
        $category->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.categories.index')
                ->with('status', 'Category deleted successfully.');
        }

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
