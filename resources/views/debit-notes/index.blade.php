<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Debit Notes" description="Manage debit adjustments, invoice-linked corrections, and customer charge recoveries from one controlled register." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Debit Notes']]" :action-url="route('finance.debit-notes.create')" action-label="Create Debit Note" />
    </x-slot>
    <section class="app-card">
        <div class="overflow-x-auto">
            <table id="debit-notes-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('finance.debit-notes.index') }}" data-datatable-storage-key="debit-notes" data-datatable-columns='@json([["data"=>"debit_note_number","name"=>"debit_note_number"],["data"=>"customer_name","name"=>"customer.name","searchable"=>false],["data"=>"invoice_number","name"=>"invoice.invoice_number","searchable"=>false],["data"=>"status_badge","name"=>"status","orderable"=>false,"searchable"=>false],["data"=>"total_display","name"=>"total"],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]])'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Debit Note</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Invoice</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Total</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($debitNotes as $debitNote)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('finance.debit-notes.show', $debitNote) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $debitNote->debit_note_number }}</a><p class="mt-1 text-sm text-slate-500">{{ optional($debitNote->debit_note_date)->format('d M Y') }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $debitNote->customer?->name ?: 'Unknown customer' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $debitNote->invoice?->invoice_number ?: 'No linked invoice' }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$debitNote->status ?: 'Open'" /></td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $debitNote->total, 2) }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('finance.debit-notes.show', $debitNote) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('finance.debit-notes.edit', $debitNote) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No debit notes are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $debitNotes->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
