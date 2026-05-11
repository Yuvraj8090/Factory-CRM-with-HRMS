@php($attendance = $attendance ?? null)

<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Attendance Entry</h2>
            <p class="mt-1 text-sm text-slate-500">Capture shift attendance, compute working hours, and keep overtime visibility accurate.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <x-input-label for="user_id" value="Employee" />
                <select id="user_id" name="user_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    <option value="">Select employee</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $attendance?->user_id) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="date" value="Attendance Date" />
                <x-text-input id="date" name="date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('date', optional($attendance?->date)->format('Y-m-d'))" required />
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="check_in" value="Check In" />
                <x-text-input id="check_in" name="check_in" type="time" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('check_in', $attendance?->check_in)" />
                <x-input-error :messages="$errors->get('check_in')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="check_out" value="Check Out" />
                <x-text-input id="check_out" name="check_out" type="time" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('check_out', $attendance?->check_out)" />
                <x-input-error :messages="$errors->get('check_out')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $attendance?->status ?? 'Present') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="notes" value="Notes" />
                <textarea id="notes" name="notes" rows="4" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('notes', $attendance?->notes) }}</textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('hrms.attendances.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $attendance ? 'Update Attendance' : 'Save Attendance' }}</button>
    </div>
</div>
