<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Category" description="Add a new product grouping so your item master remains easier to navigate and report on." icon="folder" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Categories', 'url' => route('settings.categories.index')], ['label' => 'Create']]" :back-url="route('settings.categories.index')" back-label="Back to Categories" />
    </x-slot>
    <form action="{{ route('settings.categories.store') }}" method="POST">@csrf @include('categories._form')</form>
</x-app-layout>
