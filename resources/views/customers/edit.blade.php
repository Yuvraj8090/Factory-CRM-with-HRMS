<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            eyebrow="CRM Workspace"
            title="Edit Customer"
            description="Refresh account details without losing payment and billing history."
            :back-url="route('crm.customers.show', $customer)"
            back-label="Back to Profile"
        />
    </x-slot>

    <form action="{{ route('crm.customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        @include('customers._form', ['customer' => $customer])
    </form>
</x-app-layout>
