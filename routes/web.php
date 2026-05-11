<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DebitNoteController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalesTeamController;
use App\Http\Controllers\WhatsAppTemplateController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('home');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | CRM Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role_or_permission:Super Admin|Admin|Manager|Sales Lead|Sales Rep')->name('crm.')->group(function () {
        Route::post('leads/import', [LeadController::class, 'import'])->name('leads.import');
        Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
        Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
        Route::post('leads/{lead}/send-email', [LeadController::class, 'sendEmail'])->name('leads.send-email');
        Route::post('leads/{lead}/send-whatsapp', [LeadController::class, 'sendWhatsApp'])->name('leads.send-whatsapp');
        Route::resource('leads', LeadController::class);

        Route::resource('activities', ActivityController::class);
        Route::resource('sales-teams', SalesTeamController::class);

        Route::post('customers/{customer}/send-email', [CustomerController::class, 'sendEmail'])->name('customers.send-email');
        Route::post('customers/{customer}/send-whatsapp', [CustomerController::class, 'sendWhatsApp'])->name('customers.send-whatsapp');
        Route::resource('customers', CustomerController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Finance Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role_or_permission:Super Admin|Admin|Manager|Sales Lead|Sales Rep')->name('finance.')->group(function () {
        Route::get('quotations/{quotation}/build-invoice', [InvoiceController::class, 'fromQuotation'])->name('quotations.build-invoice');
        Route::resource('quotations', QuotationController::class);
        Route::resource('invoices', InvoiceController::class);
        Route::resource('debit-notes', DebitNoteController::class);
        Route::resource('payments', PaymentController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | HRMS Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role_or_permission:Super Admin|Admin|HR Manager|HR|Manager|Staff')->name('hrms.')->group(function () {
        Route::post('attendances/import', [AttendanceController::class, 'import'])->name('attendances.import');
        Route::get('attendances/export', [AttendanceController::class, 'export'])->name('attendances.export');
        Route::resource('attendances', AttendanceController::class);

        Route::resource('departments', DepartmentController::class);
        Route::resource('designations', DesignationController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('leave-types', LeaveTypeController::class);
        Route::resource('leave-requests', LeaveRequestController::class);
        Route::post('payrolls/{payroll}/submit-review', [PayrollController::class, 'submitForReview'])->name('payrolls.submit-review');
        Route::post('payrolls/{payroll}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
        Route::get('payrolls/{payroll}/bank-transfer', [PayrollController::class, 'bankTransfer'])->name('payrolls.bank-transfer');
        Route::get('payrolls/{payroll}/items/{payrollItem}/payslip', [PayrollController::class, 'payslip'])->name('payrolls.payslip');
        Route::resource('payrolls', PayrollController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Settings & Master Data Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role_or_permission:Super Admin|Admin|Manager')->name('settings.')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('item-masters', ItemMasterController::class);
        Route::resource('whats-app-templates', WhatsAppTemplateController::class);
    });

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
