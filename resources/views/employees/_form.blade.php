@php
    $employee = $employee ?? null;
@endphp

<div class="space-y-6">
    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Employment Record</h2>
            <p class="mt-1 text-sm text-slate-500">Link user identity, org structure, onboarding details, and salary metadata.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <x-input-label for="user_id" value="Employee User" />
                <select id="user_id" name="user_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>
                    <option value="">Select employee user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $employee?->user_id) == $user->id)>{{ $user->name }}{{ $user->email ? ' • '.$user->email : '' }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="employee_code" value="Employee Code" />
                <x-text-input id="employee_code" name="employee_code" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('employee_code', $employee?->employee_code)" required />
                <x-input-error :messages="$errors->get('employee_code')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="department_id" value="Department" />
                <select id="department_id" name="department_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">Select department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id', $employee?->department_id) == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="designation_id" value="Designation" />
                <select id="designation_id" name="designation_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="">Select designation</option>
                    @foreach ($designations as $designation)
                        <option value="{{ $designation->id }}" @selected(old('designation_id', $employee?->designation_id) == $designation->id)>{{ $designation->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('designation_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="date_of_joining" value="Date of Joining" />
                <x-text-input id="date_of_joining" name="date_of_joining" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('date_of_joining', optional($employee?->date_of_joining)->format('Y-m-d'))" required />
                <x-input-error :messages="$errors->get('date_of_joining')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="date_of_birth" value="Date of Birth" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('date_of_birth', optional($employee?->date_of_birth)->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="gender" value="Gender" />
                <x-text-input id="gender" name="gender" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('gender', $employee?->gender)" />
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="marital_status" value="Marital Status" />
                <x-text-input id="marital_status" name="marital_status" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('marital_status', $employee?->marital_status)" />
                <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="blood_group" value="Blood Group" />
                <x-text-input id="blood_group" name="blood_group" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('blood_group', $employee?->blood_group)" />
                <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="salary" value="Monthly Salary" />
                <x-text-input id="salary" name="salary" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('salary', $employee?->salary ?? 0)" />
                <x-input-error :messages="$errors->get('salary')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="emergency_contact_name" value="Emergency Contact Name" />
                <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('emergency_contact_name', $employee?->emergency_contact_name)" />
                <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="emergency_contact_phone" value="Emergency Contact Phone" />
                <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('emergency_contact_phone', $employee?->emergency_contact_phone)" />
                <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="bank_name" value="Bank Name" />
                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('bank_name', $employee?->bank_name)" />
                <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="bank_account_number" value="Bank Account Number" />
                <x-text-input id="bank_account_number" name="bank_account_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('bank_account_number', $employee?->bank_account_number)" />
                <x-input-error :messages="$errors->get('bank_account_number')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="ifsc_code" value="IFSC Code" />
                <x-text-input id="ifsc_code" name="ifsc_code" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('ifsc_code', $employee?->ifsc_code)" />
                <x-input-error :messages="$errors->get('ifsc_code')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="is_active" value="Employee Status" />
                <select id="is_active" name="is_active" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">
                    <option value="1" @selected((string) old('is_active', (int) ($employee?->is_active ?? true)) === '1')>Active</option>
                    <option value="0" @selected((string) old('is_active', (int) ($employee?->is_active ?? true)) === '0')>Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('hrms.employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            {{ $employee ? 'Update Employee' : 'Save Employee' }}
        </button>
    </div>
</div>
