<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" title="Add Payment" description="Log incoming collections against open invoices with full settlement traceability." :back-url="route('finance.payments.index')" back-label="Back to Payments" />
    </x-slot>
    <form action="{{ route('finance.payments.store') }}" method="POST">@csrf @include('payments._form')</form>
</x-app-layout>
