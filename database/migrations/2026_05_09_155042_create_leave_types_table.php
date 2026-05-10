<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('leave_days_per_year')->default(0);
            $table->boolean('is_paid')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['leave_type_id']);
        });

        Schema::dropIfExists('leave_types');
    }
};
