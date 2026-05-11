<x-app-layout>
    @php
        $stages = $stages ?? $lead_stages ?? collect();
        $users = $users ?? collect();
        $salesTeams = $salesTeams ?? $sales_teams ?? collect();
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
               
                <h1 class="mt-2 text-3xl font-bold text-slate-950">Edit Lead</h1>
               
            </div>
            <a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-outline-secondary">
                Back to Lead
            </a>
        </div>
    </x-slot>

    <form action="{{ route('crm.leads.update', $lead) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="app-card app-card-body">
            <div class="border-b border-slate-200 pb-5">
                <h2 class="text-lg font-bold text-slate-950">Lead Profile</h2>
                <p class="mt-1 text-sm text-slate-500">Maintain a complete and accurate prospect profile for downstream CRM and billing operations.</p>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Lead Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('name', $lead->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="company_name" value="Company Name" />
                    <x-text-input id="company_name" name="company_name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('company_name', $lead->company_name)" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" value="Email Address" />
                    <x-text-input id="email" name="email" type="email" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('email', $lead->email)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="phone" value="Phone Number" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('phone', $lead->phone)" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="lead_source" value="Lead Source" />
                    <x-text-input id="lead_source" name="lead_source" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('lead_source', $lead->lead_source)" />
                    <x-input-error :messages="$errors->get('lead_source')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="lead_stage_id" value="Lead Stage" />
                    <select id="lead_stage_id" name="lead_stage_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900" required>
                        <option value="">Select stage</option>
                        @foreach ($stages as $stage)
                            <option value="{{ $stage->id }}" @selected(old('lead_stage_id', $lead->lead_stage_id) == $stage->id)>
                                {{ $stage->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('lead_stage_id')" class="mt-2" />
                </div>
            </div>
        </section>

        <section class="app-card app-card-body">
            <div class="border-b border-slate-200 pb-5">
                <h2 class="text-lg font-bold text-slate-950">Ownership & Territory</h2>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div>
                    <x-input-label for="assigned_to" value="Assigned Sales Rep" />
                    <select id="assigned_to" name="assigned_to" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                        <option value="">Select salesperson</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to', $lead->assigned_to) == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="assigned_team_id" value="Assigned Sales Team" />
                    <select id="assigned_team_id" name="assigned_team_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">
                        <option value="">Select team</option>
                        @foreach ($salesTeams as $team)
                            <option value="{{ $team->id }}" @selected(old('assigned_team_id', $lead->assigned_team_id) == $team->id)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('assigned_team_id')" class="mt-2" />
                </div>
                <div class="lg:col-span-2">
                    <x-input-label for="address" value="Address" />
                    <textarea id="address" name="address" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">{{ old('address', $lead->address) }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="city" value="City" />
                    <x-text-input id="city" name="city" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('city', $lead->city)" />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="state" value="State" />
                    <x-text-input id="state" name="state" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('state', $lead->state)" />
                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="country" value="Country" />
                    <x-text-input id="country" name="country" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('country', $lead->country ?: 'India')" />
                    <x-input-error :messages="$errors->get('country')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="pincode" value="Pincode" />
                    <x-text-input id="pincode" name="pincode" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('pincode', $lead->pincode)" />
                    <x-input-error :messages="$errors->get('pincode')" class="mt-2" />
                </div>
            </div>
        </section>

        <section class="app-card app-card-body">
            <div class="border-b border-slate-200 pb-5">
                <h2 class="text-lg font-bold text-slate-950">Commercial Notes</h2>
            </div>

            <div class="mt-6">
                <x-input-label for="notes" value="Internal Notes" />
                <textarea id="notes" name="notes" rows="5" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm focus:border-slate-900 focus:ring-slate-900">{{ old('notes', $lead->notes) }}</textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                Update Lead
            </button>
        </div>
    </form>
</x-app-layout>
