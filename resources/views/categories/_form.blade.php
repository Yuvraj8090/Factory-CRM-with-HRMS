@php($category = $category ?? null)
<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Category Definition</h2>
            <p class="mt-1 text-sm text-slate-500">Keep your product structure clean with category labels that support item grouping, pricing, and reporting.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-input-label for="name" value="Category Name" />
                <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $category?->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="parent_id" value="Parent Category" />
                <select id="parent_id" name="parent_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">Top-level category</option>
                    @foreach ($parent_categories as $parentCategory)
                        <option value="{{ $parentCategory->id }}" @selected((string) old('parent_id', $category?->parent_id) === (string) $parentCategory->id)>{{ $parentCategory->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="is_active" value="Status" />
                <select id="is_active" name="is_active" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="1" @selected((string) old('is_active', $category?->is_active ?? 1) === '1')>Active</option>
                    <option value="0" @selected((string) old('is_active', $category?->is_active ?? 1) === '0')>Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('description', $category?->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('settings.categories.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $category ? 'Update Category' : 'Save Category' }}</button>
    </div>
</div>
