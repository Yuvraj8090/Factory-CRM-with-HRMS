<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Leave Type" description="Update leave policy settings while keeping entitlement reporting straightforward for HR and payroll." icon="folder" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Leave Types', 'url' => route('hrms.leave-types.index')], ['label' => 'Edit']]" :back-url="route('hrms.leave-types.show', $leaveType ?? $leave_type)" back-label="Back to Leave Type" />
    </x-slot>
    <form action="{{ route('hrms.leave-types.update', $leaveType ?? $leave_type) }}" method="POST">@csrf @method('PUT') @include('leave-types._form')</form>
</x-app-layout>
