<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Department" description="Define a new HR unit so staffing, reporting lines, and role assignments stay organized from the start." icon="building" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Departments', 'url' => route('hrms.departments.index')], ['label' => 'Create']]" :back-url="route('hrms.departments.index')" back-label="Back to Departments" />
    </x-slot>
    <form action="{{ route('hrms.departments.store') }}" method="POST">@csrf @include('departments._form')</form>
</x-app-layout>
