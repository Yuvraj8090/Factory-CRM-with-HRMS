<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Edit Activity"
            description="Update the activity timing, ownership, or outcome while preserving a clear sales execution trail."
            icon="clipboard"
            :breadcrumbs="[['label' => 'CRM'], ['label' => 'Activities', 'url' => route('crm.activities.index')], ['label' => 'Edit']]"
            :back-url="route('crm.activities.show', $activity)"
            back-label="Back to Activity"
        />
    </x-slot>

    <form action="{{ route('crm.activities.update', $activity) }}" method="POST" data-local-storage-form="activities.edit.{{ $activity->id }}">
        @csrf
        @method('PUT')
        @include('activities._form')
    </form>
</x-app-layout>
