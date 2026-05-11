<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit Designation" description="Refine the job title or department mapping while preserving reporting clarity for HR and leadership." icon="tag" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Designations', 'url' => route('hrms.designations.index')], ['label' => 'Edit']]" :back-url="route('hrms.designations.show', $designation)" back-label="Back to Designation" />
    </x-slot>
    <form action="{{ route('hrms.designations.update', $designation) }}" method="POST">@csrf @method('PUT') @include('designations._form')</form>
</x-app-layout>
