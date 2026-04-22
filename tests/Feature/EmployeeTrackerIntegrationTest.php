<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTrackerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_prepare_provider_sync_point(): void
    {
        $employee = User::factory()->employee()->create();

        $this->actingAs($employee)
            ->post(route('employee.integrations.connect', ['provider' => 'strava']))
            ->assertRedirect();

        $this->assertDatabaseHas('tracker_connections', [
            'user_id' => $employee->id,
            'provider' => 'strava',
            'status' => 'planned',
        ]);
    }

    public function test_employee_can_submit_simulated_tracker_import_payload(): void
    {
        $employee = User::factory()->employee()->create();

        $this->actingAs($employee)
            ->post(route('employee.integrations.import'), [
                'provider' => 'fitbit',
                'external_reference' => 'fitbit-sync-1',
                'payload' => [
                    'activity_date' => now()->toDateString(),
                    'workout_type' => 'run',
                    'steps' => 3500,
                    'runs' => 1,
                    'distance_km' => 3.5,
                    'duration_minutes' => 24,
                    'notes' => 'Imported from fitbit payload',
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tracker_sync_imports', [
            'user_id' => $employee->id,
            'provider' => 'fitbit',
            'status' => 'completed',
            'imported_activities' => 1,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $employee->id,
            'source' => 'imported',
            'provider' => 'fitbit',
            'workout_type' => 'run',
        ]);
    }

    public function test_company_admin_cannot_access_employee_integrations(): void
    {
        /** @var User $admin */
        $admin = User::factory()->createOne();

        $this->actingAs($admin)
            ->get(route('employee.integrations.index'))
            ->assertForbidden();
    }
}
