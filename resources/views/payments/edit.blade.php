<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" title="Edit Payment" description="Correct payment allocation or references while keeping invoice balances synchronized." :back-url="route('finance.payments.show', $payment)" back-label="Back to Payment" />
    </x-slot>
    <form action="{{ route('finance.payments.update', $payment) }}" method="POST">@csrf @method('PUT') @include('payments._form', ['payment' => $payment])</form>
</x-app-layout>
