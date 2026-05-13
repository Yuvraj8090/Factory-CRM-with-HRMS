<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\Category;
use App\Models\ItemMaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemMasterController extends Controller
{
    use BuildsDataTables;

    public function index(Request $request): JsonResponse|View
    {
        $query = ItemMaster::with('category')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->orderBy('item_name');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->editColumn('item_name', fn (ItemMaster $item) => $this->recordLink(
                    $item->item_name,
                    route('settings.item-masters.show', $item),
                    [$item->item_code . ($item->unit ? ' • ' . $item->unit : '')]
                ))
                ->addColumn('category_name', fn (ItemMaster $item) => e($item->category?->name ?: 'Uncategorized'))
                ->addColumn('gst_rate_display', fn (ItemMaster $item) => e(number_format((float) $item->gst_rate, 2) . '%'))
                ->addColumn('sale_price_display', fn (ItemMaster $item) => e($this->money((float) $item->sale_price)))
                ->addColumn('status_badge', fn (ItemMaster $item) => $this->statusBadge($item->is_active ? 'Active' : 'Inactive'))
                ->addColumn('actions', fn (ItemMaster $item) => $this->actionButtons(route('settings.item-masters.show', $item), route('settings.item-masters.edit', $item)))
                ->rawColumns(['item_name', 'status_badge', 'actions'])
                ->toJson();
        }

        $items = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('item-masters.index', [
                'items' => $items,
                'categories' => Category::active()->select('id', 'name')->orderBy('name')->get(),
            ]);
        }

        return response()->json($items);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'categories' => Category::active()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('item-masters.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $item = ItemMaster::create($this->validateItem($request));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.item-masters.show', $item)
                ->with('status', 'Item created successfully.');
        }

        return response()->json($item->load('category'), 201);
    }

    public function show(Request $request, ItemMaster $itemMaster): JsonResponse|View
    {
        $itemMaster->load('category');

        if (! $request->expectsJson()) {
            return view('item-masters.show', ['itemMaster' => $itemMaster]);
        }

        return response()->json($itemMaster);
    }

    public function edit(Request $request, ItemMaster $itemMaster): JsonResponse|View
    {
        $payload = [
            'itemMaster' => $itemMaster->load('category'),
            'item' => $itemMaster->load('category'),
            'categories' => Category::active()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('item-masters.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, ItemMaster $itemMaster): JsonResponse|RedirectResponse
    {
        $itemMaster->update($this->validateItem($request, $itemMaster->id));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.item-masters.show', $itemMaster)
                ->with('status', 'Item updated successfully.');
        }

        return response()->json($itemMaster->fresh()->load('category'));
    }

    public function destroy(Request $request, ItemMaster $itemMaster): JsonResponse|RedirectResponse
    {
        $itemMaster->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.item-masters.index')
                ->with('status', 'Item deleted successfully.');
        }

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
