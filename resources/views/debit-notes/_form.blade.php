@php
    $debitNote = $debitNote ?? $debit_note ?? null;
    $lineItems = old('items', $debitNote?->items?->map(fn ($item) => [
        'item_id' => $item->item_id,
        'quantity' => $item->quantity,
        'unit_price' => $item->unit_price,
        'description' => $item->description,
    ])->toArray() ?? [['item_id' => '', 'quantity' => 1, 'unit_price' => 0, 'description' => '']]);
@endphp

<div class="space-y-6">
    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Debit Note Header</h2>
            <p class="mt-1 text-sm text-slate-500">Document a debit adjustment with the affected customer, invoice reference, and commercial reason clearly stated.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="debit_note_number" value="Debit Note Number" /><x-text-input id="debit_note_number" name="debit_note_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('debit_note_number', $debitNote?->debit_note_number)" required /><x-input-error :messages="$errors->get('debit_note_number')" class="mt-2" /></div>
            <div><x-input-label for="debit_note_date" value="Debit Note Date" /><x-text-input id="debit_note_date" name="debit_note_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('debit_note_date', optional($debitNote?->debit_note_date)->format('Y-m-d'))" required /><x-input-error :messages="$errors->get('debit_note_date')" class="mt-2" /></div>
            <div><x-input-label for="customer_id" value="Customer" /><select id="customer_id" name="customer_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>@foreach ($customers as $customer)<option value="{{ $customer->id }}" @selected((string) old('customer_id', $debitNote?->customer_id) === (string) $customer->id)>{{ $customer->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('customer_id')" class="mt-2" /></div>
            <div><x-input-label for="invoice_id" value="Invoice" /><select id="invoice_id" name="invoice_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">No linked invoice</option>@foreach ($invoices as $invoice)<option value="{{ $invoice->id }}" @selected((string) old('invoice_id', $debitNote?->invoice_id) === (string) $invoice->id)>{{ $invoice->invoice_number }}</option>@endforeach</select><x-input-error :messages="$errors->get('invoice_id')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="reason" value="Reason" /><textarea id="reason" name="reason" rows="4" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('reason', $debitNote?->reason) }}</textarea><x-input-error :messages="$errors->get('reason')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="status" value="Status" /><x-text-input id="status" name="status" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('status', $debitNote?->status ?? 'Open')" required /><x-input-error :messages="$errors->get('status')" class="mt-2" /></div>
        </div>
    </section>

    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Adjustment Lines</h2>
            <p class="mt-1 text-sm text-slate-500">Capture the items or ad hoc adjustments contributing to the debit note so totals remain auditable.</p>
        </div>
        <div class="mt-6 space-y-5">
            @foreach ($lineItems as $index => $lineItem)
                <div class="grid gap-4 app-panel p-4 lg:grid-cols-4">
                    <div class="lg:col-span-2"><x-input-label :for="'items_'.$index.'_item_id'" value="Item" /><select id="items_{{ $index }}_item_id" name="items[{{ $index }}][item_id]" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">Ad hoc adjustment</option>@foreach ($items as $item)<option value="{{ $item->id }}" @selected((string) ($lineItem['item_id'] ?? '') === (string) $item->id)>{{ $item->item_name }}</option>@endforeach</select></div>
                    <div><x-input-label :for="'items_'.$index.'_quantity'" value="Quantity" /><x-text-input :id="'items_'.$index.'_quantity'" name="items[{{ $index }}][quantity]" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="$lineItem['quantity'] ?? 1" /></div>
                    <div><x-input-label :for="'items_'.$index.'_unit_price'" value="Unit Price" /><x-text-input :id="'items_'.$index.'_unit_price'" name="items[{{ $index }}][unit_price]" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="$lineItem['unit_price'] ?? 0" /></div>
                    <div class="lg:col-span-4"><x-input-label :for="'items_'.$index.'_description'" value="Description" /><textarea id="items_{{ $index }}_description" name="items[{{ $index }}][description]" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ $lineItem['description'] ?? '' }}</textarea></div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('finance.debit-notes.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $debitNote ? 'Update Debit Note' : 'Save Debit Note' }}</button>
    </div>
</div>
