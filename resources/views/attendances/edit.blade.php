<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Edit Attendance" description="Adjust a shift record while preserving auditability of hours and status." :back-url="route('hrms.attendances.show', $attendance)" back-label="Back to Record" />
    </x-slot>
    <form action="{{ route('hrms.attendances.update', $attendance) }}" method="POST">@csrf @method('PUT') @include('attendances._form', ['attendance' => $attendance])</form>
</x-app-layout>
