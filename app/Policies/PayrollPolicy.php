<?php

namespace App\Policies;

use App\Models\PayrollPeriod;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class PayrollPolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view payrolls'); }
    public function view(User $user, PayrollPeriod $payrollPeriod): bool { return $this->allows($user, 'view payrolls'); }
    public function create(User $user): bool { return $this->allows($user, 'create payrolls'); }
    public function update(User $user, PayrollPeriod $payrollPeriod): bool { return $this->allows($user, 'update payrolls'); }
    public function delete(User $user, PayrollPeriod $payrollPeriod): bool { return $this->allows($user, 'delete payrolls'); }
    public function approve(User $user, PayrollPeriod $payrollPeriod): bool { return $this->allows($user, 'approve payrolls'); }
    public function generate(User $user): bool { return $this->allows($user, 'generate payrolls'); }
    public function restore(User $user, PayrollPeriod $payrollPeriod): bool { return $this->allows($user, 'delete payrolls'); }
    public function forceDelete(User $user, PayrollPeriod $payrollPeriod): bool { return $this->allows($user, 'delete payrolls'); }
}
