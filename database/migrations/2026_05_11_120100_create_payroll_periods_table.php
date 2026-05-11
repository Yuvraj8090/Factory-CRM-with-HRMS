<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('period_start')->index();
            $table->date('period_end')->index();
            $table->date('payout_date')->nullable()->index();
            $table->enum('status', ['draft', 'review', 'approved', 'paid'])->default('draft')->index();
            $table->decimal('total_gross', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('total_taxes', 15, 2)->default(0);
            $table->decimal('total_net', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->restrictOnDelete();
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->decimal('total_allowances', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('total_taxes', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->enum('status', ['draft', 'review', 'approved', 'paid'])->default('draft')->index();
            $table->json('breakdown')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['payroll_period_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_periods');
    }
};
