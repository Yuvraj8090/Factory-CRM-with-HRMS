<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            
            title="Create Customer"
          
            :back-url="route('crm.customers.index')"
           
        />
    </x-slot>

    <form action="{{ route('crm.customers.store') }}" method="POST">
        @csrf
        @include('customers._form')
    </form>
</x-app-layout>
