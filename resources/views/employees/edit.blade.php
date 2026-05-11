<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Edit Employee" description="Keep employee master data, financial identifiers, and org structure current." :back-url="route('hrms.employees.show', $employee)" back-label="Back to Employee" />
    </x-slot>

    <form action="{{ route('hrms.employees.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')
        @include('employees._form', ['employee' => $employee])
    </form>
</x-app-layout>
