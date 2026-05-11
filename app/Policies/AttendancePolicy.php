<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class AttendancePolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view attendances'); }
    public function view(User $user, Attendance $attendance): bool { return $this->allows($user, 'view attendances'); }
    public function create(User $user): bool { return $this->allows($user, 'create attendances'); }
    public function update(User $user, Attendance $attendance): bool { return $this->allows($user, 'update attendances'); }
    public function delete(User $user, Attendance $attendance): bool { return $this->allows($user, 'delete attendances'); }
}
