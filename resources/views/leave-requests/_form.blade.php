@php($leaveRequest = $leaveRequest ?? $leave_request ?? null)

<div class="space-y-6">
    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Leave Request</h2>
            <p class="mt-1 text-sm text-slate-500">Capture leave demand, date span, approval status, and manager accountability.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="user_id" value="Employee" /><select id="user_id" name="user_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required><option value="">Select employee</option>@foreach ($users as $user)<option value="{{ $user->id }}" @selected(old('user_id', $leaveRequest?->user_id) == $user->id)>{{ $user->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('user_id')" class="mt-2" /></div>
            <div><x-input-label for="leave_type_id" value="Leave Type" /><select id="leave_type_id" name="leave_type_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required><option value="">Select leave type</option>@foreach ($leaveTypes ?? $leave_types as $type)<option value="{{ $type->id }}" @selected(old('leave_type_id', $leaveRequest?->leave_type_id) == $type->id)>{{ $type->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" /></div>
            <div><x-input-label for="start_date" value="Start Date" /><x-text-input id="start_date" name="start_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('start_date', optional($leaveRequest?->start_date)->format('Y-m-d'))" required /><x-input-error :messages="$errors->get('start_date')" class="mt-2" /></div>
            <div><x-input-label for="end_date" value="End Date" /><x-text-input id="end_date" name="end_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('end_date', optional($leaveRequest?->end_date)->format('Y-m-d'))" required /><x-input-error :messages="$errors->get('end_date')" class="mt-2" /></div>
            <div><x-input-label for="status" value="Approval Status" /><select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(old('status', $leaveRequest?->status ?? 'Pending') === $status)>{{ $status }}</option>@endforeach</select><x-input-error :messages="$errors->get('status')" class="mt-2" /></div>
            <div><x-input-label for="approved_by" value="Approved By" /><select id="approved_by" name="approved_by" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">Select approver</option>@foreach ($users as $user)<option value="{{ $user->id }}" @selected(old('approved_by', $leaveRequest?->approved_by) == $user->id)>{{ $user->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('approved_by')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="reason" value="Reason" /><textarea id="reason" name="reason" rows="4" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('reason', $leaveRequest?->reason) }}</textarea><x-input-error :messages="$errors->get('reason')" class="mt-2" /></div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('hrms.leave-requests.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $leaveRequest ? 'Update Request' : 'Save Request' }}</button>
    </div>
</div>
