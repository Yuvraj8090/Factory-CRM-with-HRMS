<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Profile Settings"
            description="Manage your account profile, password, and account lifecycle from the same AdminLTE workspace."
            icon="cog"
            :breadcrumbs="[['label' => 'Settings'], ['label' => 'Profile']]"
        />
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-outline card-danger">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
