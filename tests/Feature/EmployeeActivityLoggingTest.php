<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeActivityLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_view_tracker_driven_health_stats(): void
    {
        $employee = User::factory()->employee()->create();

        ActivityLog::create([
            'user_id' => $employee->id,
            'activity_date' => now()->toDateString(),
            'workout_type' => 'run',
            'steps' => 6400,
            'runs' => 1,
            'distance_km' => 6.4,
            'duration_minutes' => 42,
            'source' => 'imported',
            'provider' => 'fitbit',
            'notes' => 'Evening run',
            'raw_payload' => [],
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $employee->id,
            'workout_type' => 'run',
            'steps' => 6400,
            'runs' => 1,
            'duration_minutes' => 42,
            'source' => 'imported',
        ]);

        $this->actingAs($employee)
            ->get(route('employee.health.index'))
            ->assertOk()
            ->assertSeeText('Health Stats')
            ->assertSeeText('Recent Tracker Activity')
            ->assertSeeText('Tracker Connections');
    }

    public function test_employee_cannot_submit_manual_health_entry(): void
    {
        $employee = User::factory()->employee()->create();

        $this->actingAs($employee)
            ->from(route('employee.health.index'))
            ->post(route('employee.activities.store'), [
                'activity_date' => now()->toDateString(),
                'workout_type' => 'walk',
                'steps' => 0,
                'runs' => 0,
                'distance_km' => 0,
                'duration_minutes' => 0,
            ])
            ->assertForbidden();
    }

    public function test_company_admin_cannot_access_employee_activity_routes(): void
    {
        /** @var User $admin */
        $admin = User::factory()->createOne();

        $this->actingAs($admin)
            ->get(route('employee.health.index'))
            ->assertForbidden();
    }
}
