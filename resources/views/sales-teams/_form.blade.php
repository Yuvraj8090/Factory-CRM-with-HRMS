@php
    $salesTeam = $salesTeam ?? $sales_team ?? null;
@endphp

<div class="space-y-6">
    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Sales Team Profile</h2>
            <p class="mt-1 text-sm text-slate-500">Define team leadership, operating purpose, and activation status for lead distribution and sales ownership.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-input-label for="name" value="Team Name" />
                <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $salesTeam?->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="team_lead_id" value="Team Lead" />
                <select id="team_lead_id" name="team_lead_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">Select a team lead</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected((string) old('team_lead_id', $salesTeam?->team_lead_id) === (string) $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('team_lead_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="is_active" value="Status" />
                <select id="is_active" name="is_active" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    <option value="1" @selected((string) old('is_active', $salesTeam?->is_active ?? 1) === '1')>Active</option>
                    <option value="0" @selected((string) old('is_active', $salesTeam?->is_active ?? 1) === '0')>Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
            </div>
            <div class="lg:col-span-2">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('description', $salesTeam?->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('crm.sales-teams.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $salesTeam ? 'Update Team' : 'Save Team' }}</button>
    </div>
</div>
