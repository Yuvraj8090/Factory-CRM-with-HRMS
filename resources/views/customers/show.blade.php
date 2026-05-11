<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            eyebrow="CRM Workspace"
            :title="$customer->name"
            description="Customer account snapshot with commercial history, contact context, and account controls."
            :back-url="route('crm.customers.index')"
            back-label="Back to Customers"
        >
            <a href="{{ route('crm.customers.edit', $customer) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Edit Customer</a>
        </x-crud.page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.9fr)]">
            <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Company</p>
                        <p class="mt-2 text-lg font-semibold text-slate-950">{{ $customer->company_name ?: 'Direct account' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Status</p>
                        <div class="mt-2"><x-crud.status-badge :value="$customer->status" /></div>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Email</p>
                        <p class="mt-2 text-sm text-slate-700">{{ $customer->email ?: 'Not available' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Phone</p>
                        <p class="mt-2 text-sm text-slate-700">{{ $customer->phone ?: 'Not available' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Location</p>
                        <p class="mt-2 text-sm text-slate-700">{{ collect([$customer->address, $customer->city, $customer->state, $customer->country, $customer->pincode])->filter()->implode(', ') ?: 'Not available' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Tax Details</p>
                        <p class="mt-2 text-sm text-slate-700">GST: {{ $customer->gst_number ?: 'N/A' }}<br>PAN: {{ $customer->pan_number ?: 'N/A' }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
                <h2 class="text-lg font-bold text-slate-950">Commercial Snapshot</h2>
                <div class="mt-5 grid gap-4">
                    <div class="rounded-2xl bg-slate-50 px-4 py-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Customer Type</p>
                        <p class="mt-2 text-lg font-bold capitalize text-slate-950">{{ $customer->customer_type }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Credit Limit</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $customer->credit_limit, 2) }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Opening Balance</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $customer->opening_balance, 2) }}</p>
                    </div>
                </div>
            </section>
        </div>

        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <h2 class="text-lg font-bold text-slate-950">Activity Timeline</h2>
            <p class="mt-2 text-sm text-slate-500">Invoices, quotations, messages, and communication history can be surfaced here as the remaining modules move onto the shared web pattern.</p>
        </section>
    </div>
</x-app-layout>
