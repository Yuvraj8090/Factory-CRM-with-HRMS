<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" title="Edit Invoice" description="Adjust billing lines, dates, or commercial terms while keeping tax logic intact." :back-url="route('finance.invoices.show', $invoice)" back-label="Back to Invoice" />
    </x-slot>
    <form action="{{ route('finance.invoices.update', $invoice) }}" method="POST">@csrf @method('PUT') @include('invoices._form', ['invoice' => $invoice])</form>
</x-app-layout>
