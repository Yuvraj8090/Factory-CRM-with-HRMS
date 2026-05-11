<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Category" description="Refine the category structure while keeping parent-child relationships and reporting clarity intact." icon="folder" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Categories', 'url' => route('settings.categories.index')], ['label' => 'Edit']]" :back-url="route('settings.categories.show', $category)" back-label="Back to Category" />
    </x-slot>
    <form action="{{ route('settings.categories.update', $category) }}" method="POST">@csrf @method('PUT') @include('categories._form')</form>
</x-app-layout>
