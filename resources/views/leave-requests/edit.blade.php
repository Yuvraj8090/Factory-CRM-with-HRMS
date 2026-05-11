<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Edit Leave Request" description="Update dates, approvals, or reason while preserving the leave history context." :back-url="route('hrms.leave-requests.show', $leaveRequest ?? $leave_request)" back-label="Back to Request" />
    </x-slot>
    <form action="{{ route('hrms.leave-requests.update', $leaveRequest ?? $leave_request) }}" method="POST">@csrf @method('PUT') @include('leave-requests._form', ['leaveRequest' => $leaveRequest ?? $leave_request])</form>
</x-app-layout>
