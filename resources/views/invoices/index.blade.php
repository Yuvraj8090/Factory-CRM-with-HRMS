<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" title="Invoices" description="Monitor billing output, GST-ready totals, and collection status from one finance control board." :action-url="route('finance.invoices.create')" action-label="Create Invoice" />
    </x-slot>

    <section class="app-card">
        <form id="invoices-filters" method="GET" data-local-storage-form="invoices.filters" data-local-storage-clear-on-submit="false" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]">
            <div><x-input-label for="search" value="Search" /><x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Invoice or customer..." /></div>
            <div><x-input-label for="invoice_status" value="Invoice Status" /><select id="invoice_status" name="invoice_status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All invoice statuses</option>@foreach ($invoiceStatuses as $status)<option value="{{ $status }}" @selected(request('invoice_status') === $status)>{{ $status }}</option>@endforeach</select></div>
            <div><x-input-label for="payment_status" value="Payment Status" /><select id="payment_status" name="payment_status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All payment statuses</option>@foreach ($paymentStatuses as $status)<option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ $status }}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="btn btn-primary btn-block">Apply</button></div>
        </form>

        <div class="overflow-x-auto">
            <table id="invoices-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('finance.invoices.index') }}" data-datatable-filter-form="#invoices-filters" data-datatable-storage-key="invoices" data-datatable-columns='{{ \Illuminate\Support\Js::from([["data"=>"invoice_number","name"=>"invoice_number"],["data"=>"customer_name","name"=>"customer.name","searchable"=>false],["data"=>"due_date_display","name"=>"due_date"],["data"=>"invoice_status_badge","name"=>"invoice_status","orderable"=>false,"searchable"=>false],["data"=>"payment_status_badge","name"=>"payment_status","orderable"=>false,"searchable"=>false],["data"=>"total_display","name"=>"total"],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]]) }}'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Invoice</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Due Date</th><th class="px-6 py-4">Invoice Status</th><th class="px-6 py-4">Payment Status</th><th class="px-6 py-4">Total</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($invoices as $invoice)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('finance.invoices.show', $invoice) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $invoice->invoice_number }}</a><p class="mt-1 text-sm text-slate-500">{{ optional($invoice->invoice_date)->format('d M Y') }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $invoice->customer?->name ?: 'Unknown customer' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($invoice->due_date)->format('d M Y') ?: 'Not set' }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$invoice->invoice_status" /></td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$invoice->payment_status" /></td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $invoice->total, 2) }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('finance.invoices.show', $invoice) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('finance.invoices.edit', $invoice) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-sm text-slate-500">No invoices matched the current filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $invoices->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
