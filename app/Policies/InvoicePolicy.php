<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class InvoicePolicy
{
    use ChecksPermissions;

    public function viewAny(User $user): bool { return $this->allows($user, 'view invoices'); }
    public function view(User $user, Invoice $invoice): bool { return $this->allows($user, 'view invoices'); }
    public function create(User $user): bool { return $this->allows($user, 'create invoices'); }
    public function update(User $user, Invoice $invoice): bool { return $this->allows($user, 'update invoices'); }
    public function delete(User $user, Invoice $invoice): bool { return $this->allows($user, 'delete invoices'); }
    public function send(User $user, Invoice $invoice): bool { return $this->allows($user, 'send invoices'); }
    public function markPaid(User $user, Invoice $invoice): bool { return $this->allows($user, 'mark-paid invoices'); }
    public function restore(User $user, Invoice $invoice): bool { return $this->allows($user, 'delete invoices'); }
    public function forceDelete(User $user, Invoice $invoice): bool { return $this->allows($user, 'delete invoices'); }
}
