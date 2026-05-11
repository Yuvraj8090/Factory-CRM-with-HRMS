<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Debit Note" description="Update the commercial correction while keeping the recovery rationale and affected invoice clear." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Debit Notes', 'url' => route('finance.debit-notes.index')], ['label' => 'Edit']]" :back-url="route('finance.debit-notes.show', $debitNote ?? $debit_note)" back-label="Back to Debit Note" />
    </x-slot>
    <form action="{{ route('finance.debit-notes.update', $debitNote ?? $debit_note) }}" method="POST">@csrf @method('PUT') @include('debit-notes._form')</form>
</x-app-layout>
