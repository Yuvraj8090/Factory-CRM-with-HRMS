<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItemMaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemMasterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = ItemMaster::with('category')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->orderBy('item_name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($items);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'categories' => Category::active()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $item = ItemMaster::create($this->validateItem($request));

        return response()->json($item->load('category'), 201);
    }

    public function show(ItemMaster $itemMaster): JsonResponse
    {
        return response()->json($itemMaster->load('category'));
    }

    public function edit(ItemMaster $itemMaster): JsonResponse
    {
        return response()->json([
            'item' => $itemMaster->load('category'),
            'categories' => Category::active()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ItemMaster $itemMaster): JsonResponse
    {
        $itemMaster->update($this->validateItem($request, $itemMaster->id));

        return response()->json($itemMaster->fresh()->load('category'));
    }

    public function destroy(ItemMaster $itemMaster): JsonResponse
    {
        $itemMaster->delete();

        return response()->json(['message' => 'Item deleted successfully.']);
    }

    protected function validateItem(Request $request, ?int $itemId = null): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'item_code' => ['required', 'string', 'max:255', 'unique:item_masters,item_code,' . $itemId],
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:20'],
            'hsn_code' => ['nullable', 'string', 'max:20'],
            'gst_rate' => ['nullable', 'numeric', 'min:0'],
            'opening_stock' => ['nullable', 'numeric', 'min:0'],
            'reorder_level' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
