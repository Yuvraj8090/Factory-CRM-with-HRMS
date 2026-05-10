<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('team_lead_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('sales_team_id')->references('id')->on('sales_teams')->nullOnDelete();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('assigned_team_id')->references('id')->on('sales_teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_team_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sales_team_id']);
        });

        Schema::dropIfExists('sales_teams');
    }
};
