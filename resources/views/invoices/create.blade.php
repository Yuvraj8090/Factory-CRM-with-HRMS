<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" title="Create Invoice" description="Issue a GST-aware invoice with customer, item, and payment terms aligned." :back-url="route('finance.invoices.index')" back-label="Back to Invoices" />
    </x-slot>
    <form action="{{ route('finance.invoices.store') }}" method="POST">@csrf @include('invoices._form')</form>
</x-app-layout>
