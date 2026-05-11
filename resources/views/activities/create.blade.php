<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Create Activity"
            description="Capture a sales action with all the context needed for follow-up, reporting, and accountability."
            icon="clipboard"
            :breadcrumbs="[['label' => 'CRM'], ['label' => 'Activities', 'url' => route('crm.activities.index')], ['label' => 'Create']]"
            :back-url="route('crm.activities.index')"
            back-label="Back to Activities"
        />
    </x-slot>

    <form action="{{ route('crm.activities.store') }}" method="POST">
        @csrf
        @include('activities._form')
    </form>
</x-app-layout>
