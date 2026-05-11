<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Sales Team" description="Set up a delivery-ready sales pod with a lead owner and operating notes for campaign coordination." icon="users" :breadcrumbs="[['label' => 'CRM'], ['label' => 'Sales Teams', 'url' => route('crm.sales-teams.index')], ['label' => 'Create']]" :back-url="route('crm.sales-teams.index')" back-label="Back to Sales Teams" />
    </x-slot>
    <form action="{{ route('crm.sales-teams.store') }}" method="POST">
        @csrf
        @include('sales-teams._form')
    </form>
</x-app-layout>
