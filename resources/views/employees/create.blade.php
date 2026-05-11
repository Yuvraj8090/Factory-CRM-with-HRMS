<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Add Employee" description="Create a structured employee record that HR, payroll, and attendance can all rely on." :back-url="route('hrms.employees.index')" back-label="Back to Employees" />
    </x-slot>

    <form action="{{ route('hrms.employees.store') }}" method="POST">
        @csrf
        @include('employees._form')
    </form>
</x-app-layout>
