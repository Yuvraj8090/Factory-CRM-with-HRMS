@php($leaveType = $leaveType ?? $leave_type ?? null)
<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Leave Type Configuration</h2>
            <p class="mt-1 text-sm text-slate-500">Define the leave buckets your HR team can approve so entitlement tracking stays consistent through the year.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-input-label for="name" value="Leave Type Name" />
                <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $leaveType?->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="leave_days_per_year" value="Days Per Year" />
                <x-text-input id="leave_days_per_year" name="leave_days_per_year" type="number" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('leave_days_per_year', $leaveType?->leave_days_per_year ?? 0)" required />
                <x-input-error :messages="$errors->get('leave_days_per_year')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="is_paid" value="Compensation Type" />
                <select id="is_paid" name="is_paid" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="1" @selected((string) old('is_paid', $leaveType?->is_paid ?? 1) === '1')>Paid Leave</option>
                    <option value="0" @selected((string) old('is_paid', $leaveType?->is_paid ?? 1) === '0')>Unpaid Leave</option>
                </select>
                <x-input-error :messages="$errors->get('is_paid')" class="mt-2" />
            </div>
        </div>
    </section>
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('hrms.leave-types.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $leaveType ? 'Update Leave Type' : 'Save Leave Type' }}</button>
    </div>
</div>
