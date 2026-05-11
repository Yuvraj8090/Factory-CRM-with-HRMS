<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class EmployeePolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view employees'); }
    public function view(User $user, Employee $employee): bool { return $this->allows($user, 'view employees'); }
    public function create(User $user): bool { return $this->allows($user, 'create employees'); }
    public function update(User $user, Employee $employee): bool { return $this->allows($user, 'update employees'); }
    public function delete(User $user, Employee $employee): bool { return $this->allows($user, 'delete employees'); }
    public function restore(User $user, Employee $employee): bool { return $this->allows($user, 'delete employees'); }
    public function forceDelete(User $user, Employee $employee): bool { return $this->allows($user, 'delete employees'); }
}
