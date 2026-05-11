<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Quotation" description="Prepare a commercial offer that sales and finance can confidently present to the customer." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Quotations', 'url' => route('finance.quotations.index')], ['label' => 'Create']]" :back-url="route('finance.quotations.index')" back-label="Back to Quotations" />
    </x-slot>
    <form action="{{ route('finance.quotations.store') }}" method="POST">@csrf @include('quotations._form')</form>
</x-app-layout>
