@php
    $whatsAppTemplate = $whatsAppTemplate ?? null;
    $variables = old('variables', $whatsAppTemplate?->variables ?? []);
@endphp

<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Template Configuration</h2>
            <p class="mt-1 text-sm text-slate-500">Prepare an approved WhatsApp template with the right category, identifiers, and merge fields for outbound messaging.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="template_name" value="Template Name" /><x-text-input id="template_name" name="template_name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('template_name', $whatsAppTemplate?->template_name)" required /><x-input-error :messages="$errors->get('template_name')" class="mt-2" /></div>
            <div><x-input-label for="template_id" value="Provider Template ID" /><x-text-input id="template_id" name="template_id" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('template_id', $whatsAppTemplate?->template_id)" required /><x-input-error :messages="$errors->get('template_id')" class="mt-2" /></div>
            <div><x-input-label for="category" value="Category" /><select id="category" name="category" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">@foreach ($categories as $category)<option value="{{ $category }}" @selected(old('category', $whatsAppTemplate?->category) === $category)>{{ $category }}</option>@endforeach</select><x-input-error :messages="$errors->get('category')" class="mt-2" /></div>
            <div><x-input-label for="is_active" value="Status" /><select id="is_active" name="is_active" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="1" @selected((string) old('is_active', $whatsAppTemplate?->is_active ?? 1) === '1')>Active</option><option value="0" @selected((string) old('is_active', $whatsAppTemplate?->is_active ?? 1) === '0')>Inactive</option></select><x-input-error :messages="$errors->get('is_active')" class="mt-2" /></div>
            <div class="lg:col-span-2">
                <x-input-label value="Template Variables" />
                <div class="mt-2 space-y-3 rounded-2xl border border-slate-200 p-4">
                    @forelse ($variables as $index => $variable)
                        <x-text-input :name="'variables['.$index.']'" type="text" class="block w-full rounded-2xl border-slate-200" :value="$variable" placeholder="Variable name" />
                    @empty
                        <x-text-input name="variables[0]" type="text" class="block w-full rounded-2xl border-slate-200" placeholder="Variable name" />
                    @endforelse
                </div>
                <x-input-error :messages="$errors->get('variables')" class="mt-2" />
            </div>
        </div>
    </section>
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('settings.whats-app-templates.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $whatsAppTemplate ? 'Update Template' : 'Save Template' }}</button>
    </div>
</div>
