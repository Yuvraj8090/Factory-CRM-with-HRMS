@php($designation = $designation ?? null)
<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Designation Setup</h2>
            <p class="mt-1 text-sm text-slate-500">Define job titles and map them to the right department so reporting lines stay structured and understandable.</p>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-input-label for="name" value="Designation Name" />
                <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $designation?->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="department_id" value="Department" />
                <select id="department_id" name="department_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected((string) old('department_id', $designation?->department_id) === (string) $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
            </div>
        </div>
    </section>
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('hrms.designations.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $designation ? 'Update Designation' : 'Save Designation' }}</button>
    </div>
</div>
