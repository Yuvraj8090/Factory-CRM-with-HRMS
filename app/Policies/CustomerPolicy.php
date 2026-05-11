<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class CustomerPolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view customers'); }
    public function view(User $user, Customer $customer): bool { return $this->allows($user, 'view customers'); }
    public function create(User $user): bool { return $this->allows($user, 'create customers'); }
    public function update(User $user, Customer $customer): bool { return $this->allows($user, 'update customers'); }
    public function delete(User $user, Customer $customer): bool { return $this->allows($user, 'delete customers'); }
    public function restore(User $user, Customer $customer): bool { return $this->allows($user, 'delete customers'); }
    public function forceDelete(User $user, Customer $customer): bool { return $this->allows($user, 'delete customers'); }
}
