<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\ActivityType;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\User;
use Database\Seeders\ActivityDataSeeder;
use Database\Seeders\LeadStageSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_status_can_be_updated_inline_and_logged(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->seed([
            RolesAndPermissionsSeeder::class,
            LeadStageSeeder::class,
            ActivityDataSeeder::class,
        ]);

        $user = User::factory()->create();
        $user->assignRole('Sales Lead');

        $lead = Lead::create([
            'name' => 'Factory Buyer',
            'company_name' => 'Factory Buyer Pvt Ltd',
            'lead_stage_id' => LeadStage::query()->where('is_default', true)->value('id'),
            'created_by' => $user->id,
        ]);

        $activity = Activity::create([
            'lead_id' => $lead->id,
            'activity_type_id' => ActivityType::query()->where('name', 'Call')->value('id'),
            'activity_status_id' => ActivityStatus::query()->where('name', 'Pending')->value('id'),
            'assigned_to' => $user->id,
            'subject' => 'Initial discovery call',
            'description' => 'Qualify the requirement and timeline.',
            'created_by' => $user->id,
        ]);

        $completedStatusId = ActivityStatus::query()->where('name', 'Completed')->value('id');

        $response = $this->actingAs($user)->patchJson(route('crm.activities.update-status', $activity), [
            'activity_status_id' => $completedStatusId,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('status', 'Completed');

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'activity_status_id' => $completedStatusId,
        ]);

        $this->assertDatabaseHas('activity_status_logs', [
            'activity_id' => $activity->id,
            'from_status_id' => ActivityStatus::query()->where('name', 'Pending')->value('id'),
            'to_status_id' => $completedStatusId,
            'changed_by' => $user->id,
        ]);
    }

    public function test_activities_index_returns_server_side_datatable_payload(): void
    {
        $this->seed([
            RolesAndPermissionsSeeder::class,
            LeadStageSeeder::class,
            ActivityDataSeeder::class,
        ]);

        $user = User::factory()->create();
        $user->assignRole('Sales Lead');

        $lead = Lead::create([
            'name' => 'Plant Prospect',
            'lead_stage_id' => LeadStage::query()->where('is_default', true)->value('id'),
            'created_by' => $user->id,
        ]);

        Activity::create([
            'lead_id' => $lead->id,
            'activity_type_id' => ActivityType::query()->where('name', 'Email')->value('id'),
            'activity_status_id' => ActivityStatus::query()->where('name', 'Pending')->value('id'),
            'assigned_to' => $user->id,
            'subject' => 'Send capability deck',
            'description' => 'Share company overview and product range.',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->withHeader('X-Requested-With', 'XMLHttpRequest')->get(route('crm.activities.index', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data' => [
                    ['subject', 'status_dropdown', 'actions'],
                ],
            ]);
    }
}
