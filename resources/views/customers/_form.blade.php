@php
    $customer = $customer ?? null;
    $customerTypes = $customerTypes ?? $customer_types ?? ['retail', 'wholesale', 'institutional'];
    $statuses = $statuses ?? ['Active', 'Inactive', 'Blocked'];
@endphp

<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Customer Profile</h2>
            <p class="mt-1 text-sm text-slate-500">Maintain commercial identity, billing details, and account classification in one place.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <x-input-label for="name" value="Customer Name" />
                <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $customer?->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="company_name" value="Company Name" />
                <x-text-input id="company_name" name="company_name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('company_name', $customer?->company_name)" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" value="Email Address" />
                <x-text-input id="email" name="email" type="email" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('email', $customer?->email)" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="phone" value="Phone Number" />
                <x-text-input id="phone" name="phone" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('phone', $customer?->phone)" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="address" value="Address" />
                <textarea id="address" name="address" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">{{ old('address', $customer?->address) }}</textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="city" value="City" />
                <x-text-input id="city" name="city" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('city', $customer?->city)" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="state" value="State" />
                <x-text-input id="state" name="state" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('state', $customer?->state)" />
                <x-input-error :messages="$errors->get('state')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="country" value="Country" />
                <x-text-input id="country" name="country" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('country', $customer?->country ?? 'India')" />
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pincode" value="Pincode" />
                <x-text-input id="pincode" name="pincode" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('pincode', $customer?->pincode)" />
                <x-input-error :messages="$errors->get('pincode')" class="mt-2" />
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Commercial Controls</h2>
            <p class="mt-1 text-sm text-slate-500">Configure statutory data, exposure limits, and account health.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <x-input-label for="gst_number" value="GST Number" />
                <x-text-input id="gst_number" name="gst_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('gst_number', $customer?->gst_number)" />
                <x-input-error :messages="$errors->get('gst_number')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pan_number" value="PAN Number" />
                <x-text-input id="pan_number" name="pan_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('pan_number', $customer?->pan_number)" />
                <x-input-error :messages="$errors->get('pan_number')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="customer_type" value="Customer Type" />
                <select id="customer_type" name="customer_type" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900" required>
                    @foreach ($customerTypes as $type)
                        <option value="{{ $type }}" @selected(old('customer_type', $customer?->customer_type ?? 'retail') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('customer_type')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="status" value="Account Status" />
                <select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $customer?->status ?? 'Active') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="opening_balance" value="Opening Balance" />
                <x-text-input id="opening_balance" name="opening_balance" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('opening_balance', $customer?->opening_balance ?? 0)" />
                <x-input-error :messages="$errors->get('opening_balance')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="credit_limit" value="Credit Limit" />
                <x-text-input id="credit_limit" name="credit_limit" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('credit_limit', $customer?->credit_limit ?? 0)" />
                <x-input-error :messages="$errors->get('credit_limit')" class="mt-2" />
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('crm.customers.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
            {{ $customer ? 'Update Customer' : 'Save Customer' }}
        </button>
    </div>
</div>
