<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Payroll" description="Generate payroll periods, monitor approval progress, and export bank-ready payment instructions." :action-url="route('hrms.payrolls.create')" action-label="Generate Payroll" />
    </x-slot>

    <section class="app-card">
        <form id="payrolls-filters" method="GET" data-local-storage-form="payrolls.filters" data-local-storage-clear-on-submit="false" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[220px_auto]">
            <div>
                <x-input-label for="year" value="Payroll Year" />
                <select id="year" name="year" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">All years</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" @selected((string) request('year') === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end"><button type="submit" class="btn btn-primary btn-block sm:w-auto">Apply</button></div>
        </form>

        <div class="overflow-x-auto">
            <table id="payrolls-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('hrms.payrolls.index') }}" data-datatable-filter-form="#payrolls-filters" data-datatable-storage-key="payrolls" data-datatable-columns='{{ \Illuminate\Support\Js::from([["data"=>"name","name"=>"name"],["data"=>"period_display","name"=>"period_start"],["data"=>"employees_total","name"=>"items_count"],["data"=>"status_badge","name"=>"status","orderable"=>false,"searchable"=>false],["data"=>"net_total_display","name"=>"total_net"],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]]) }}'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Payroll Run</th><th class="px-6 py-4">Period</th><th class="px-6 py-4">Employees</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Net Total</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payrolls as $payroll)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.payrolls.show', $payroll) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $payroll->name }}</a><p class="mt-1 text-sm text-slate-500">{{ optional($payroll->payout_date)->format('d M Y') ?: 'Payout pending' }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($payroll->period_start)->format('d M Y') }} to {{ optional($payroll->period_end)->format('d M Y') }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $payroll->items_count }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="ucfirst($payroll->status)" /></td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $payroll->total_net, 2) }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.payrolls.show', $payroll) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('hrms.payrolls.edit', $payroll) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No payroll runs available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $payrolls->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
