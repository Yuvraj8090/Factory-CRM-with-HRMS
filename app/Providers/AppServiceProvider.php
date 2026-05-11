<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\LeaveRequest;
use App\Models\Payment;
use App\Models\PayrollPeriod;
use App\Policies\AttendancePolicy;
use App\Policies\CustomerPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\LeaveRequestPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PayrollPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(PayrollPeriod::class, PayrollPolicy::class);
    }
}
