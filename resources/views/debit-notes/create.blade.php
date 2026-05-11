<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Debit Note" description="Record a recoverable adjustment or commercial correction with complete invoice and customer context." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Debit Notes', 'url' => route('finance.debit-notes.index')], ['label' => 'Create']]" :back-url="route('finance.debit-notes.index')" back-label="Back to Debit Notes" />
    </x-slot>
    <form action="{{ route('finance.debit-notes.store') }}" method="POST">@csrf @include('debit-notes._form')</form>
</x-app-layout>
