<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Designation" description="Create a role title that can be linked to employee records, departments, and compensation structures." icon="tag" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Designations', 'url' => route('hrms.designations.index')], ['label' => 'Create']]" :back-url="route('hrms.designations.index')" back-label="Back to Designations" />
    </x-slot>
    <form action="{{ route('hrms.designations.store') }}" method="POST">@csrf @include('designations._form')</form>
</x-app-layout>
