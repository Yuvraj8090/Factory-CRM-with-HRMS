<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="WhatsApp Templates" description="Manage approved messaging templates for customer communication, operational alerts, and transactional updates." icon="chat" :breadcrumbs="[['label' => 'Settings'], ['label' => 'WhatsApp Templates']]" :action-url="route('settings.whats-app-templates.create')" action-label="Add Template" />
    </x-slot>
    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Template</th><th class="px-6 py-4">Category</th><th class="px-6 py-4">Messages</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($templates as $template)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('settings.whats-app-templates.show', $template) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $template->template_name }}</a><p class="mt-1 text-sm text-slate-500">{{ $template->template_id }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $template->category ?: 'Uncategorized' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $template->messages_count ?? $template->messages?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$template->is_active ? 'Active' : 'Inactive'" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('settings.whats-app-templates.show', $template) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('settings.whats-app-templates.edit', $template) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No WhatsApp templates have been configured yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $templates->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
