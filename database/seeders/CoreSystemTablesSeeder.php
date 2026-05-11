<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoreSystemTablesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::limit(5)->get();
        $timestamp = now()->getTimestamp();

        foreach ($users as $index => $user) {
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => hash('sha256', 'reset-token-' . $user->email),
                'created_at' => now()->subDays($index),
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            DB::table('sessions')->insert([
                'id' => Str::uuid()->toString(),
                'user_id' => $users->random()->id,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'payload' => base64_encode(json_encode(['demo' => true, 'index' => $i], JSON_THROW_ON_ERROR)),
                'last_activity' => $timestamp - ($i * 900),
            ]);

            DB::table('cache')->insert([
                'key' => 'demo_cache_key_' . $i,
                'value' => serialize(['record' => $i, 'generated_at' => now()->toDateTimeString()]),
                'expiration' => $timestamp + 3600 + ($i * 120),
            ]);

            DB::table('cache_locks')->insert([
                'key' => 'demo_lock_' . $i,
                'owner' => 'worker-' . $i,
                'expiration' => $timestamp + 600 + ($i * 60),
            ]);

            DB::table('jobs')->insert([
                'queue' => fake()->randomElement(['default', 'emails', 'exports']),
                'payload' => json_encode(['displayName' => 'DemoJob' . $i, 'job' => 'App\\Jobs\\DemoJob'], JSON_THROW_ON_ERROR),
                'attempts' => fake()->numberBetween(0, 2),
                'reserved_at' => null,
                'available_at' => $timestamp + ($i * 60),
                'created_at' => $timestamp - ($i * 120),
            ]);

            DB::table('job_batches')->insert([
                'id' => (string) Str::uuid(),
                'name' => 'Demo Batch ' . $i,
                'total_jobs' => fake()->numberBetween(5, 20),
                'pending_jobs' => fake()->numberBetween(0, 5),
                'failed_jobs' => fake()->numberBetween(0, 2),
                'failed_job_ids' => json_encode([], JSON_THROW_ON_ERROR),
                'options' => json_encode(['queue' => 'default'], JSON_THROW_ON_ERROR),
                'cancelled_at' => null,
                'created_at' => $timestamp - ($i * 3600),
                'finished_at' => $timestamp - ($i * 1800),
            ]);

            DB::table('failed_jobs')->insert([
                'uuid' => (string) Str::uuid(),
                'connection' => 'database',
                'queue' => fake()->randomElement(['default', 'emails']),
                'payload' => json_encode(['displayName' => 'FailedDemoJob' . $i], JSON_THROW_ON_ERROR),
                'exception' => 'Demo exception stack trace for seeded failed job #' . $i,
                'failed_at' => now()->subHours($i),
            ]);
        }
    }
}
