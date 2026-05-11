<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            eyebrow="CRM Workspace"
            title="Create Customer"
            description="Set up a clean commercial account profile for invoicing, collections, and support."
            :back-url="route('crm.customers.index')"
            back-label="Back to Customers"
        />
    </x-slot>

    <form action="{{ route('crm.customers.store') }}" method="POST">
        @csrf
        @include('customers._form')
    </form>
</x-app-layout>
