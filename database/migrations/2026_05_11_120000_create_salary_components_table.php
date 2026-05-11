<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['allowance', 'deduction', 'tax']);
            $table->enum('calculation_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('default_amount', 15, 2)->default(0);
            $table->boolean('is_taxable')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salary_component_id')->constrained()->cascadeOnDelete();
            $table->enum('value_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'salary_component_id', 'effective_from'], 'employee_salary_components_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salary_components');
        Schema::dropIfExists('salary_components');
    }
};
