<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Generate Payroll" description="Create a payroll period and generate employee-wise pay lines from salary structures." :back-url="route('hrms.payrolls.index')" back-label="Back to Payroll" />
    </x-slot>
    <form action="{{ route('hrms.payrolls.store') }}" method="POST">@csrf @include('payrolls._form')</form>
</x-app-layout>
