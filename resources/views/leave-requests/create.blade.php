<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Add Leave Request" description="Log the request with enough detail for HR review and roster planning." :back-url="route('hrms.leave-requests.index')" back-label="Back to Leave Requests" />
    </x-slot>
    <form action="{{ route('hrms.leave-requests.store') }}" method="POST">@csrf @include('leave-requests._form')</form>
</x-app-layout>
