<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Item" description="Register a new product or SKU with pricing, unit, and GST details ready for quotations and invoices." icon="cube" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Item Masters', 'url' => route('settings.item-masters.index')], ['label' => 'Create']]" :back-url="route('settings.item-masters.index')" back-label="Back to Item Masters" />
    </x-slot>
    <form action="{{ route('settings.item-masters.store') }}" method="POST">@csrf @include('item-masters._form')</form>
</x-app-layout>
