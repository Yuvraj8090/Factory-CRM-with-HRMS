<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            
            title="Edit Customer"
           
            :back-url="route('crm.customers.show', $customer)"
           
        />
    </x-slot>

    <form action="{{ route('crm.customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        @include('customers._form', ['customer' => $customer])
    </form>
</x-app-layout>
