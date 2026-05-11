<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$whatsAppTemplate->template_name" description="Template detail page for reviewing approval readiness, variable mapping, and message usage volume." icon="chat" :breadcrumbs="[['label' => 'Settings'], ['label' => 'WhatsApp Templates', 'url' => route('settings.whats-app-templates.index')], ['label' => 'Details']]" :back-url="route('settings.whats-app-templates.index')" back-label="Back to Templates" :action-url="route('settings.whats-app-templates.edit', $whatsAppTemplate)" action-label="Edit Template" />
    </x-slot>
    <div class="grid gap-6 md:grid-cols-3">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Provider ID</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $whatsAppTemplate->template_id }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Category</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $whatsAppTemplate->category ?: 'Uncategorized' }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$whatsAppTemplate->is_active ? 'Active' : 'Inactive'" /></div></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60 md:col-span-3">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Variables</p>
            <div class="mt-4 flex flex-wrap gap-2">
                @forelse (($whatsAppTemplate->variables ?? []) as $variable)
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">{{ $variable }}</span>
                @empty
                    <p class="text-sm text-slate-500">No variable placeholders are configured for this template yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
