<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::with(['parent', 'children', 'items'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($categories);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'parent_categories' => Category::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $category = Category::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($category->load('parent'), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category->load(['parent', 'children', 'items']));
    }

    public function edit(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category->load('parent'),
            'parent_categories' => Category::whereKeyNot($category->id)->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $category->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:' . $category->id],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($category->fresh()->load('parent'));
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
