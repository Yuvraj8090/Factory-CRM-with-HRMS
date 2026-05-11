<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Edit Payroll" description="Adjust the payroll header details while preserving the generated employee lines." :back-url="route('hrms.payrolls.show', $payroll)" back-label="Back to Payroll" />
    </x-slot>
    <form action="{{ route('hrms.payrolls.update', $payroll) }}" method="POST">@csrf @method('PUT') @include('payrolls._form', ['payroll' => $payroll])</form>
</x-app-layout>
