@php
    $quotation = $quotation ?? null;
    $statuses = $statuses ?? ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired'];
    $lineItems = old('items', $quotation?->items?->map(fn ($item) => [
        'item_id' => $item->item_id,
        'quantity' => $item->quantity,
        'unit_price' => $item->unit_price,
        'tax_rate' => $item->tax_rate,
        'description' => $item->description,
    ])->toArray() ?? [['item_id' => '', 'quantity' => 1, 'unit_price' => 0, 'tax_rate' => 0, 'description' => '']]);
@endphp

<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Quotation Header</h2>
            <p class="mt-1 text-sm text-slate-500">Define the commercial terms, customer context, and validity window before sharing an offer with the customer.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="quotation_number" value="Quotation Number" /><x-text-input id="quotation_number" name="quotation_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('quotation_number', $quotation?->quotation_number)" required /><x-input-error :messages="$errors->get('quotation_number')" class="mt-2" /></div>
            <div><x-input-label for="customer_id" value="Customer" /><select id="customer_id" name="customer_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>@foreach ($customers as $customer)<option value="{{ $customer->id }}" @selected((string) old('customer_id', $quotation?->customer_id) === (string) $customer->id)>{{ $customer->name }}{{ $customer->company_name ? ' • '.$customer->company_name : '' }}</option>@endforeach</select><x-input-error :messages="$errors->get('customer_id')" class="mt-2" /></div>
            <div><x-input-label for="quotation_date" value="Quotation Date" /><x-text-input id="quotation_date" name="quotation_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('quotation_date', optional($quotation?->quotation_date)->format('Y-m-d'))" required /><x-input-error :messages="$errors->get('quotation_date')" class="mt-2" /></div>
            <div><x-input-label for="valid_until" value="Valid Until" /><x-text-input id="valid_until" name="valid_until" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('valid_until', optional($quotation?->valid_until)->format('Y-m-d'))" /><x-input-error :messages="$errors->get('valid_until')" class="mt-2" /></div>
            <div><x-input-label for="discount_type" value="Discount Type" /><select id="discount_type" name="discount_type" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">No discount</option><option value="percentage" @selected(old('discount_type', $quotation?->discount_type) === 'percentage')>Percentage</option><option value="fixed" @selected(old('discount_type', $quotation?->discount_type) === 'fixed')>Fixed Amount</option></select><x-input-error :messages="$errors->get('discount_type')" class="mt-2" /></div>
            <div><x-input-label for="discount_value" value="Discount Value" /><x-text-input id="discount_value" name="discount_value" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('discount_value', $quotation?->discount_value ?? 0)" /><x-input-error :messages="$errors->get('discount_value')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="status" value="Status" /><select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(old('status', $quotation?->status ?? 'Draft') === $status)>{{ $status }}</option>@endforeach</select><x-input-error :messages="$errors->get('status')" class="mt-2" /></div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Line Items</h2>
            <p class="mt-1 text-sm text-slate-500">Capture the quoted products, quantities, pricing, and tax rates so finance can generate downstream documents reliably.</p>
        </div>
        <div class="mt-6 space-y-5">
            @foreach ($lineItems as $index => $lineItem)
                <div class="grid gap-4 rounded-2xl border border-slate-200 p-4 lg:grid-cols-4">
                    <div class="lg:col-span-2">
                        <x-input-label :for="'items_'.$index.'_item_id'" value="Item" />
                        <select id="items_{{ $index }}_item_id" name="items[{{ $index }}][item_id]" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                            <option value="">Ad hoc line item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" @selected((string) ($lineItem['item_id'] ?? '') === (string) $item->id)>{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><x-input-label :for="'items_'.$index.'_quantity'" value="Quantity" /><x-text-input :id="'items_'.$index.'_quantity'" name="items[{{ $index }}][quantity]" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="$lineItem['quantity'] ?? 1" /></div>
                    <div><x-input-label :for="'items_'.$index.'_unit_price'" value="Unit Price" /><x-text-input :id="'items_'.$index.'_unit_price'" name="items[{{ $index }}][unit_price]" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="$lineItem['unit_price'] ?? 0" /></div>
                    <div><x-input-label :for="'items_'.$index.'_tax_rate'" value="Tax Rate" /><x-text-input :id="'items_'.$index.'_tax_rate'" name="items[{{ $index }}][tax_rate]" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="$lineItem['tax_rate'] ?? 0" /></div>
                    <div class="lg:col-span-3"><x-input-label :for="'items_'.$index.'_description'" value="Description" /><textarea id="items_{{ $index }}_description" name="items[{{ $index }}][description]" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ $lineItem['description'] ?? '' }}</textarea></div>
                </div>
            @endforeach
            <x-input-error :messages="$errors->get('items')" class="mt-2" />
        </div>
    </section>

    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="notes" value="Notes" /><textarea id="notes" name="notes" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('notes', $quotation?->notes) }}</textarea></div>
            <div><x-input-label for="terms_conditions" value="Terms & Conditions" /><textarea id="terms_conditions" name="terms_conditions" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('terms_conditions', $quotation?->terms_conditions) }}</textarea></div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('finance.quotations.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $quotation ? 'Update Quotation' : 'Save Quotation' }}</button>
    </div>
</div>
