<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class PaymentPolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view payments'); }
    public function view(User $user, Payment $payment): bool { return $this->allows($user, 'view payments'); }
    public function create(User $user): bool { return $this->allows($user, 'create payments'); }
    public function update(User $user, Payment $payment): bool { return $this->allows($user, 'update payments'); }
    public function delete(User $user, Payment $payment): bool { return $this->allows($user, 'delete payments'); }
}
