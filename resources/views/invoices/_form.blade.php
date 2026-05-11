@php
    $invoice = $invoice ?? null;
    $invoiceStatuses = $invoiceStatuses ?? $invoice_statuses ?? ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'];
    $lineItems = old('items', $invoice
        ? $invoice->items->map(fn ($item) => [
            'item_id' => $item->item_id,
            'hsn_code' => $item->hsn_code,
            'quantity' => $item->quantity,
            'unit' => $item->unit,
            'unit_price' => $item->unit_price,
            'gst_rate' => $item->gst_rate,
            'description' => $item->description,
        ])->values()->all()
        : [[
            'item_id' => null,
            'hsn_code' => null,
            'quantity' => 1,
            'unit' => null,
            'unit_price' => 0,
            'gst_rate' => 0,
            'description' => null,
        ]]);
@endphp

<div
    x-data="{
        items: {{ Illuminate\Support\Js::from($lineItems) }},
        catalog: {{ Illuminate\Support\Js::from($items->map(fn ($item) => ['id' => $item->id, 'name' => $item->item_name, 'unit' => $item->unit, 'hsn_code' => $item->hsn_code, 'sale_price' => $item->sale_price, 'gst_rate' => $item->gst_rate])->values()) }},
        addRow() {
            this.items.push({ item_id: null, hsn_code: '', quantity: 1, unit: '', unit_price: 0, gst_rate: 0, description: '' });
        },
        removeRow(index) {
            if (this.items.length > 1) this.items.splice(index, 1);
        },
        syncItem(index) {
            const selected = this.catalog.find((item) => String(item.id) === String(this.items[index].item_id));
            if (!selected) return;
            this.items[index].hsn_code = selected.hsn_code ?? '';
            this.items[index].unit = selected.unit ?? '';
            this.items[index].unit_price = selected.sale_price ?? 0;
            this.items[index].gst_rate = selected.gst_rate ?? 0;
            this.items[index].description = this.items[index].description || selected.name;
        },
        subtotal() {
            return this.items.reduce((sum, item) => sum + ((Number(item.quantity) || 0) * (Number(item.unit_price) || 0)), 0);
        },
        taxAmount() {
            return this.items.reduce((sum, item) => {
                const lineSubtotal = (Number(item.quantity) || 0) * (Number(item.unit_price) || 0);
                return sum + (lineSubtotal * ((Number(item.gst_rate) || 0) / 100));
            }, 0);
        },
        grandTotal() {
            return this.subtotal() + this.taxAmount();
        }
    }"
    class="space-y-6"
>
    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Invoice Header</h2>
            <p class="mt-1 text-sm text-slate-500">Define customer, billing dates, and commercial status before itemized GST calculation.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="invoice_number" value="Invoice Number" /><x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('invoice_number', $invoice?->invoice_number ?? request('invoice_number', 'INV-'.now()->format('YmdHis')))" required /><x-input-error :messages="$errors->get('invoice_number')" class="mt-2" /></div>
            <div><x-input-label for="invoice_date" value="Invoice Date" /><x-text-input id="invoice_date" name="invoice_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('invoice_date', optional($invoice?->invoice_date)->format('Y-m-d') ?? now()->format('Y-m-d'))" required /><x-input-error :messages="$errors->get('invoice_date')" class="mt-2" /></div>
            <div><x-input-label for="customer_id" value="Customer" /><select id="customer_id" name="customer_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required><option value="">Select customer</option>@foreach ($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id', $invoice?->customer_id) == $customer->id)>{{ $customer->name }}{{ $customer->company_name ? ' • '.$customer->company_name : '' }}</option>@endforeach</select><x-input-error :messages="$errors->get('customer_id')" class="mt-2" /></div>
            <div><x-input-label for="quotation_id" value="Source Quotation" /><select id="quotation_id" name="quotation_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">No quotation linked</option>@foreach ($quotations as $quotation)<option value="{{ $quotation->id }}" @selected(old('quotation_id', $invoice?->quotation_id ?? request('quotation_id')) == $quotation->id)>{{ $quotation->quotation_number }}</option>@endforeach</select><x-input-error :messages="$errors->get('quotation_id')" class="mt-2" /></div>
            <div><x-input-label for="invoice_status" value="Invoice Status" /><select id="invoice_status" name="invoice_status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>@foreach ($invoiceStatuses as $status)<option value="{{ $status }}" @selected(old('invoice_status', $invoice?->invoice_status ?? 'Draft') === $status)>{{ $status }}</option>@endforeach</select><x-input-error :messages="$errors->get('invoice_status')" class="mt-2" /></div>
            <div><x-input-label for="due_date" value="Due Date" /><x-text-input id="due_date" name="due_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('due_date', optional($invoice?->due_date)->format('Y-m-d'))" /><x-input-error :messages="$errors->get('due_date')" class="mt-2" /></div>
            <div><x-input-label for="discount_type" value="Discount Type" /><select id="discount_type" name="discount_type" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">No discount</option><option value="percentage" @selected(old('discount_type', $invoice?->discount_type) === 'percentage')>Percentage</option><option value="fixed" @selected(old('discount_type', $invoice?->discount_type) === 'fixed')>Fixed amount</option></select><x-input-error :messages="$errors->get('discount_type')" class="mt-2" /></div>
            <div><x-input-label for="discount_value" value="Discount Value" /><x-text-input id="discount_value" name="discount_value" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('discount_value', $invoice?->discount_value ?? 0)" /><x-input-error :messages="$errors->get('discount_value')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="notes" value="Notes" /><textarea id="notes" name="notes" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('notes', $invoice?->notes) }}</textarea><x-input-error :messages="$errors->get('notes')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="terms_conditions" value="Terms & Conditions" /><textarea id="terms_conditions" name="terms_conditions" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('terms_conditions', $invoice?->terms_conditions) }}</textarea><x-input-error :messages="$errors->get('terms_conditions')" class="mt-2" /></div>
        </div>
    </section>

    <section class="app-card app-card-body">
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h2 class="text-lg font-bold text-slate-950">Invoice Items</h2>
                <p class="mt-1 text-sm text-slate-500">Line-level GST inputs drive the final invoice value and intra/inter-state split.</p>
            </div>
            <button type="button" @click="addRow()" class="btn btn-outline-secondary btn-sm">Add Row</button>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="table table-hover app-data-table text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <tr><th class="px-4 py-3">Item</th><th class="px-4 py-3">HSN</th><th class="px-4 py-3">Qty</th><th class="px-4 py-3">Unit</th><th class="px-4 py-3">Rate</th><th class="px-4 py-3">GST %</th><th class="px-4 py-3">Description</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, index) in items" :key="index">
                        <tr>
                            <td class="px-4 py-3">
                                <select :name="`items[${index}][item_id]`" x-model="row.item_id" @change="syncItem(index)" class="block w-56 rounded-2xl border-slate-200 text-sm shadow-sm">
                                    <option value="">Custom line</option>
                                    <template x-for="option in catalog" :key="option.id">
                                        <option :value="option.id" x-text="option.name"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="px-4 py-3"><input :name="`items[${index}][hsn_code]`" x-model="row.hsn_code" class="block w-28 rounded-2xl border-slate-200 text-sm shadow-sm" /></td>
                            <td class="px-4 py-3"><input :name="`items[${index}][quantity]`" x-model="row.quantity" type="number" step="0.01" class="block w-24 rounded-2xl border-slate-200 text-sm shadow-sm" /></td>
                            <td class="px-4 py-3"><input :name="`items[${index}][unit]`" x-model="row.unit" class="block w-24 rounded-2xl border-slate-200 text-sm shadow-sm" /></td>
                            <td class="px-4 py-3"><input :name="`items[${index}][unit_price]`" x-model="row.unit_price" type="number" step="0.01" class="block w-28 rounded-2xl border-slate-200 text-sm shadow-sm" /></td>
                            <td class="px-4 py-3"><input :name="`items[${index}][gst_rate]`" x-model="row.gst_rate" type="number" step="0.01" class="block w-24 rounded-2xl border-slate-200 text-sm shadow-sm" /></td>
                            <td class="px-4 py-3"><input :name="`items[${index}][description]`" x-model="row.description" class="block w-64 rounded-2xl border-slate-200 text-sm shadow-sm" /></td>
                            <td class="px-4 py-3 text-right"><button type="button" @click="removeRow(index)" class="text-xs font-semibold text-rose-600 transition hover:text-rose-700">Remove</button></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        @if ($errors->has('items') || $errors->has('items.*'))
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                Review the invoice item rows and fix the highlighted validation issues before saving.
            </div>
        @endif
    </section>

    <section class="app-card app-card-body">
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="app-surface px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Subtotal</p><p class="mt-2 text-lg font-bold text-slate-950">₹<span x-text="subtotal().toFixed(2)"></span></p></div>
            <div class="app-surface px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Tax Amount</p><p class="mt-2 text-lg font-bold text-slate-950">₹<span x-text="taxAmount().toFixed(2)"></span></p></div>
            <div class="app-surface px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Projected Total</p><p class="mt-2 text-lg font-bold text-slate-950">₹<span x-text="grandTotal().toFixed(2)"></span></p></div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('finance.invoices.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $invoice ? 'Update Invoice' : 'Save Invoice' }}</button>
    </div>
</div>
