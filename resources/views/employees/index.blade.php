<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Employees" description="Maintain workforce records, org structure assignments, and compensation baselines." :action-url="route('hrms.employees.create')" action-label="Add Employee" />
    </x-slot>

    <section class="app-card">
        <form method="GET" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_260px_auto]">
            <div>
                <x-input-label for="search" value="Search" />
                <x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Employee code, name, or email..." />
            </div>
            <div>
                <x-input-label for="department_id" value="Department" />
                <select id="department_id" name="department_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">All departments</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected((string) request('department_id') === (string) $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="btn btn-primary btn-block">Apply</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-hover app-data-table text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Designation</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4">Salary</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($employees as $employee)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5">
                                <a href="{{ route('hrms.employees.show', $employee) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $employee->user?->name ?? 'Unknown user' }}</a>
                                <p class="mt-1 text-sm text-slate-500">{{ $employee->employee_code }}</p>
                                <p class="mt-2 text-xs text-slate-500">{{ $employee->user?->email ?: 'No email' }}</p>
                            </td>
                            <td class="px-6 py-5 text-slate-600">{{ $employee->department?->name ?: 'Unassigned' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $employee->designation?->name ?: 'Unassigned' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($employee->date_of_joining)->format('d M Y') }}</td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $employee->salary, 2) }}</td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('hrms.employees.show', $employee) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                    <a href="{{ route('hrms.employees.edit', $employee) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No employee records matched the current filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-6 py-4">{{ $employees->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
