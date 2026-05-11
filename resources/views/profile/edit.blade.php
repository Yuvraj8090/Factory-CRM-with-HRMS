<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Profile Settings"
            description="Manage your account details, password, and session security so your workspace access remains current and protected."
            icon="cog"
            :breadcrumbs="[['label' => 'Settings'], ['label' => 'Profile']]"
        />
    </x-slot>

    <div class="space-y-6">
        <div class="space-y-6">
            <div class="rounded-3xl border border-white/70 bg-white p-4 shadow-sm shadow-slate-200/60 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-3xl border border-white/70 bg-white p-4 shadow-sm shadow-slate-200/60 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-3xl border border-white/70 bg-white p-4 shadow-sm shadow-slate-200/60 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
