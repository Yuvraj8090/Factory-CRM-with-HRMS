@php($payroll = $payroll ?? null)

<div class="space-y-6">
    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Payroll Run Setup</h2>
            <p class="mt-1 text-sm text-slate-500">Select the pay period, payout date, and participating employees for this payroll cycle.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="name" value="Payroll Name" /><x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $payroll?->name)" /></div>
            <div><x-input-label for="payout_date" value="Payout Date" /><x-text-input id="payout_date" name="payout_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('payout_date', optional($payroll?->payout_date)->format('Y-m-d'))" /></div>
            <div><x-input-label for="period_start" value="Period Start" /><x-text-input id="period_start" name="period_start" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('period_start', optional($payroll?->period_start)->format('Y-m-d') ?? now()->startOfMonth()->format('Y-m-d'))" required /></div>
            <div><x-input-label for="period_end" value="Period End" /><x-text-input id="period_end" name="period_end" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('period_end', optional($payroll?->period_end)->format('Y-m-d') ?? now()->endOfMonth()->format('Y-m-d'))" required /></div>
            <div class="lg:col-span-2"><x-input-label for="notes" value="Notes" /><textarea id="notes" name="notes" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('notes', $payroll?->notes) }}</textarea></div>
            @if (! $payroll)
                <div class="lg:col-span-2">
                    <x-input-label value="Employees Included" />
                    <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($employees as $employee)
                            <label class="flex items-center gap-3 app-panel px-4 py-3 text-sm text-slate-700">
                                <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900" @checked(collect(old('employee_ids'))->contains($employee->id))>
                                <span>{{ $employee->user?->name ?? 'Unknown user' }} • {{ $employee->employee_code }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Leave blank to include all active employees.</p>
                </div>
            @endif
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('hrms.payrolls.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $payroll ? 'Update Payroll' : 'Generate Payroll' }}</button>
    </div>
</div>
