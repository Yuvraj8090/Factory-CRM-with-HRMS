<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Add Attendance" description="Record a day’s shift movement with enough detail for HR and payroll downstream." :back-url="route('hrms.attendances.index')" back-label="Back to Attendance" />
    </x-slot>
    <form action="{{ route('hrms.attendances.store') }}" method="POST">@csrf @include('attendances._form')</form>
</x-app-layout>
