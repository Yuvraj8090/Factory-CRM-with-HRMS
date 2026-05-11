<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Sales Team" description="Refine leadership assignments and team operating details without losing accountability context." icon="users" :breadcrumbs="[['label' => 'CRM'], ['label' => 'Sales Teams', 'url' => route('crm.sales-teams.index')], ['label' => 'Edit']]" :back-url="route('crm.sales-teams.show', $salesTeam ?? $sales_team)" back-label="Back to Team" />
    </x-slot>
    <form action="{{ route('crm.sales-teams.update', $salesTeam ?? $sales_team) }}" method="POST">
        @csrf
        @method('PUT')
        @include('sales-teams._form')
    </form>
</x-app-layout>
