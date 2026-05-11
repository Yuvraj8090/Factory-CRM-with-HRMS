<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $categories = Category::with(['parent', 'children', 'items'])
            ->withCount('items')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

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
