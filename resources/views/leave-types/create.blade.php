<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create Leave Type" description="Set up a new leave category with a yearly entitlement and clear payroll treatment." icon="folder" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Leave Types', 'url' => route('hrms.leave-types.index')], ['label' => 'Create']]" :back-url="route('hrms.leave-types.index')" back-label="Back to Leave Types" />
    </x-slot>
    <form action="{{ route('hrms.leave-types.store') }}" method="POST">@csrf @include('leave-types._form')</form>
</x-app-layout>
