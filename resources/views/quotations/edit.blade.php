<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Quotation" description="Refine quoted pricing, dates, and commercial notes while keeping the proposal history understandable." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Quotations', 'url' => route('finance.quotations.index')], ['label' => 'Edit']]" :back-url="route('finance.quotations.show', $quotation)" back-label="Back to Quotation" />
    </x-slot>
    <form action="{{ route('finance.quotations.update', $quotation) }}" method="POST">@csrf @method('PUT') @include('quotations._form')</form>
</x-app-layout>
