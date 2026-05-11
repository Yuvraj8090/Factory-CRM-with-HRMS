<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
           
            title="Customers"
           
            :action-url="route('crm.customers.create')"
            action-label="Add New Customer"
        />
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
            <form method="GET" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]">
                <div>
                    <x-input-label for="search" value="Search" />
                    <x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Customer, company, GST, phone..." />
                </div>
                <div>
                    <x-input-label for="customer_type" value="Type" />
                    <select id="customer_type" name="customer_type" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                        <option value="">All types</option>
                        @foreach ($customerTypes as $type)
                            <option value="{{ $type }}" @selected(request('customer_type') === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Apply</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Invoices</th>
                            <th class="px-6 py-4">Credit</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($customers as $customer)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-5">
                                    <a href="{{ route('crm.customers.show', $customer) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $customer->name }}</a>
                                    <p class="mt-1 text-sm text-slate-500">{{ $customer->company_name ?: 'Direct account' }}</p>
                                    <p class="mt-2 text-xs text-slate-500">{{ $customer->email ?: 'No email' }}{{ $customer->phone ? ' • '.$customer->phone : '' }}</p>
                                </td>
                                <td class="px-6 py-5 capitalize text-slate-600">{{ $customer->customer_type }}</td>
                                <td class="px-6 py-5"><x-crud.status-badge :value="$customer->status" /></td>
                                <td class="px-6 py-5 text-slate-600">{{ $customer->invoices_count }}</td>
                                <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $customer->credit_limit, 2) }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('crm.customers.show', $customer) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a>
                                        <a href="{{ route('crm.customers.edit', $customer) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No customers matched your filters yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $customers->withQueryString()->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
