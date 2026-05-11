<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Quotations" description="Track customer offers, their commercial status, and the proposals most likely to convert into revenue." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Quotations']]" :action-url="route('finance.quotations.create')" action-label="Create Quotation" />
    </x-slot>
    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <form method="GET" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[220px_auto]">
            <div><x-input-label for="status" value="Status" /><select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All statuses</option>@foreach (($statuses ?? ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired']) as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Apply Filters</button></div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Quotation</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Date</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Total</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($quotations as $quotation)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('finance.quotations.show', $quotation) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $quotation->quotation_number }}</a><p class="mt-1 text-sm text-slate-500">{{ optional($quotation->valid_until)->format('d M Y') ? 'Valid until '.optional($quotation->valid_until)->format('d M Y') : 'No validity date set' }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $quotation->customer?->name ?: 'Unknown customer' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($quotation->quotation_date)->format('d M Y') }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$quotation->status" /></td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $quotation->total, 2) }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('finance.quotations.show', $quotation) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('finance.quotations.edit', $quotation) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No quotations are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $quotations->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
