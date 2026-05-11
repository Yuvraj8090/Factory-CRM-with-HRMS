<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class LeaveRequestPolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view leave-requests'); }
    public function view(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'view leave-requests'); }
    public function create(User $user): bool { return $this->allows($user, 'create leave-requests'); }
    public function update(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'update leave-requests'); }
    public function delete(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'delete leave-requests'); }
    public function approve(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'approve leave-requests'); }
    public function reject(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'reject leave-requests'); }
    public function restore(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'delete leave-requests'); }
    public function forceDelete(User $user, LeaveRequest $leaveRequest): bool { return $this->allows($user, 'delete leave-requests'); }
}
