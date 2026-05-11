<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Department" description="Update department positioning or status while keeping workforce structure understandable for HR and operations." icon="building" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Departments', 'url' => route('hrms.departments.index')], ['label' => 'Edit']]" :back-url="route('hrms.departments.show', $department)" back-label="Back to Department" />
    </x-slot>
    <form action="{{ route('hrms.departments.update', $department) }}" method="POST">@csrf @method('PUT') @include('departments._form')</form>
</x-app-layout>
