<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Item" description="Update commercial or tax-critical item data while keeping product reporting accurate and aligned." icon="cube" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Item Masters', 'url' => route('settings.item-masters.index')], ['label' => 'Edit']]" :back-url="route('settings.item-masters.show', $item ?? $itemMaster ?? $item_master)" back-label="Back to Item" />
    </x-slot>
    <form action="{{ route('settings.item-masters.update', $item ?? $itemMaster ?? $item_master) }}" method="POST">@csrf @method('PUT') @include('item-masters._form')</form>
</x-app-layout>
