<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DebitNote;
use App\Models\Invoice;
use App\Models\ItemMaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DebitNoteController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $debitNotes = DebitNote::with(['customer', 'invoice', 'items.item', 'creator'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('debit_note_date')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('debit-notes.index', compact('debitNotes'));
        }

        return response()->json($debitNotes);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'customers' => Customer::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'invoices' => Invoice::query()->select('id', 'invoice_number', 'customer_id')->orderByDesc('invoice_date')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('debit-notes.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validateDebitNote($request);

        $debitNote = DB::transaction(function () use ($data) {
            $note = DebitNote::create(array_merge($this->calculateTotals($data), [
                'debit_note_number' => $data['debit_note_number'],
                'debit_note_date' => $data['debit_note_date'],
                'invoice_id' => $data['invoice_id'],
                'customer_id' => $data['customer_id'],
                'reason' => $data['reason'] ?? null,
                'status' => $data['status'],
                'created_by' => auth()->id(),
            ]));

            foreach ($data['items'] as $itemData) {
                $line = $this->calculateDebitLine($itemData);
                $note->items()->create($line);
            }

            return $note->load(['customer', 'invoice', 'items.item', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.debit-notes.show', $debitNote)
                ->with('status', 'Debit note created successfully.');
        }

        return response()->json($debitNote, 201);
    }

    public function show(Request $request, DebitNote $debitNote): JsonResponse|View
    {
        $debitNote->load(['customer', 'invoice', 'items.item', 'creator']);

        if (! $request->expectsJson()) {
            return view('debit-notes.show', compact('debitNote'));
        }

        return response()->json($debitNote);
    }

    public function edit(Request $request, DebitNote $debitNote): JsonResponse|View
    {
        $payload = [
            'debitNote' => $debitNote->load(['customer', 'invoice', 'items.item', 'creator']),
            'debit_note' => $debitNote->load(['customer', 'invoice', 'items.item', 'creator']),
            'customers' => Customer::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'invoices' => Invoice::query()->select('id', 'invoice_number', 'customer_id')->orderByDesc('invoice_date')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('debit-notes.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, DebitNote $debitNote): JsonResponse|RedirectResponse
    {
        $data = $this->validateDebitNote($request, $debitNote->id);

        $debitNote = DB::transaction(function () use ($debitNote, $data) {
            $debitNote->update(array_merge($this->calculateTotals($data), [
                'debit_note_number' => $data['debit_note_number'],
                'debit_note_date' => $data['debit_note_date'],
                'invoice_id' => $data['invoice_id'],
                'customer_id' => $data['customer_id'],
                'reason' => $data['reason'] ?? null,
                'status' => $data['status'],
            ]));

            $debitNote->items()->delete();
            foreach ($data['items'] as $itemData) {
                $debitNote->items()->create($this->calculateDebitLine($itemData));
            }

            return $debitNote->fresh()->load(['customer', 'invoice', 'items.item', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.debit-notes.show', $debitNote)
                ->with('status', 'Debit note updated successfully.');
        }

        return response()->json($debitNote);
    }

    public function destroy(Request $request, DebitNote $debitNote): JsonResponse|RedirectResponse
    {
        $debitNote->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.debit-notes.index')
                ->with('status', 'Debit note deleted successfully.');
        }

        return response()->json(['message' => 'Debit note deleted successfully.']);
    }

    protected function validateDebitNote(Request $request, ?int $debitNoteId = null): array
    {
        return $request->validate([
            'debit_note_number' => ['required', 'string', 'max:255', 'unique:debit_notes,debit_note_number,' . $debitNoteId],
            'debit_note_date' => ['required', 'date'],
            'invoice_id' => ['required', 'exists:invoices,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'reason' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['nullable', 'exists:item_masters,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ]);
    }

    protected function calculateTotals(array $data): array
    {
        $subtotal = 0;
        $taxAmount = 0;

        foreach ($data['items'] as $itemData) {
            $line = $this->calculateDebitLine($itemData);
            $subtotal += ($line['quantity'] * $line['unit_price']);
            $taxAmount += $line['tax_amount'];
        }

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($subtotal + $taxAmount, 2),
        ];
    }

    protected function calculateDebitLine(array $itemData): array
    {
        $quantity = (float) $itemData['quantity'];
        $unitPrice = (float) $itemData['unit_price'];
        $taxAmount = (float) ($itemData['tax_amount'] ?? 0);

        return [
            'item_id' => $itemData['item_id'] ?? null,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'tax_amount' => round($taxAmount, 2),
            'total' => round(($quantity * $unitPrice) + $taxAmount, 2),
            'description' => $itemData['description'] ?? null,
        ];
    }
}
