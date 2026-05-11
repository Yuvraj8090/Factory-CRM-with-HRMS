@php($item = $item ?? $itemMaster ?? $item_master ?? null)
<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Item Master Details</h2>
            <p class="mt-1 text-sm text-slate-500">Store the commercial and tax-ready definition of each sellable or billable product in one dependable master record.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="category_id" value="Category" /><select id="category_id" name="category_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">Uncategorized</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('category_id', $item?->category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('category_id')" class="mt-2" /></div>
            <div><x-input-label for="item_code" value="Item Code" /><x-text-input id="item_code" name="item_code" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('item_code', $item?->item_code)" required /><x-input-error :messages="$errors->get('item_code')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="item_name" value="Item Name" /><x-text-input id="item_name" name="item_name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('item_name', $item?->item_name)" required /><x-input-error :messages="$errors->get('item_name')" class="mt-2" /></div>
            <div><x-input-label for="unit" value="Unit" /><x-text-input id="unit" name="unit" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('unit', $item?->unit)" required /><x-input-error :messages="$errors->get('unit')" class="mt-2" /></div>
            <div><x-input-label for="hsn_code" value="HSN Code" /><x-text-input id="hsn_code" name="hsn_code" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('hsn_code', $item?->hsn_code)" /><x-input-error :messages="$errors->get('hsn_code')" class="mt-2" /></div>
            <div><x-input-label for="gst_rate" value="GST Rate" /><x-text-input id="gst_rate" name="gst_rate" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('gst_rate', $item?->gst_rate ?? 0)" /><x-input-error :messages="$errors->get('gst_rate')" class="mt-2" /></div>
            <div><x-input-label for="opening_stock" value="Opening Stock" /><x-text-input id="opening_stock" name="opening_stock" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('opening_stock', $item?->opening_stock ?? 0)" /><x-input-error :messages="$errors->get('opening_stock')" class="mt-2" /></div>
            <div><x-input-label for="reorder_level" value="Reorder Level" /><x-text-input id="reorder_level" name="reorder_level" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('reorder_level', $item?->reorder_level ?? 0)" /><x-input-error :messages="$errors->get('reorder_level')" class="mt-2" /></div>
            <div><x-input-label for="sale_price" value="Sale Price" /><x-text-input id="sale_price" name="sale_price" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('sale_price', $item?->sale_price ?? 0)" /><x-input-error :messages="$errors->get('sale_price')" class="mt-2" /></div>
            <div><x-input-label for="purchase_price" value="Purchase Price" /><x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('purchase_price', $item?->purchase_price ?? 0)" /><x-input-error :messages="$errors->get('purchase_price')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="description" value="Description" /><textarea id="description" name="description" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('description', $item?->description) }}</textarea><x-input-error :messages="$errors->get('description')" class="mt-2" /></div>
            <div><x-input-label for="is_active" value="Status" /><select id="is_active" name="is_active" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="1" @selected((string) old('is_active', $item?->is_active ?? 1) === '1')>Active</option><option value="0" @selected((string) old('is_active', $item?->is_active ?? 1) === '0')>Inactive</option></select><x-input-error :messages="$errors->get('is_active')" class="mt-2" /></div>
        </div>
    </section>
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('settings.item-masters.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $item ? 'Update Item' : 'Save Item' }}</button>
    </div>
</div>
