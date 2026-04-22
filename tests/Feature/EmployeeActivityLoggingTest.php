<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeActivityLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_log_activity_and_view_it(): void
    {
        $employee = User::factory()->employee()->create();

        $this->actingAs($employee)
            ->post(route('employee.activities.store'), [
                'activity_date' => now()->toDateString(),
                'workout_type' => 'run',
                'steps' => 6400,
                'runs' => 1,
                'distance_km' => 6.4,
                'duration_minutes' => 42,
                'notes' => 'Evening run',
            ])
            ->assertRedirect(route('employee.activities.index'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $employee->id,
            'workout_type' => 'run',
            'steps' => 6400,
            'runs' => 1,
            'duration_minutes' => 42,
            'source' => 'manual',
        ]);

        $this->actingAs($employee)
            ->get(route('employee.activities.index'))
            ->assertOk()
            ->assertSeeText('Activity Logging')
            ->assertSeeText('Recent Activity');
    }

    public function test_activity_log_requires_at_least_one_metric(): void
    {
        $employee = User::factory()->employee()->create();

        $this->actingAs($employee)
            ->from(route('employee.activities.index'))
            ->post(route('employee.activities.store'), [
                'activity_date' => now()->toDateString(),
                'workout_type' => 'walk',
                'steps' => 0,
                'runs' => 0,
                'distance_km' => 0,
                'duration_minutes' => 0,
            ])
            ->assertRedirect(route('employee.activities.index'))
            ->assertSessionHasErrors('metrics');
    }

    public function test_company_admin_cannot_access_employee_activity_routes(): void
    {
        /** @var User $admin */
        $admin = User::factory()->createOne();

        $this->actingAs($admin)
            ->get(route('employee.activities.index'))
            ->assertForbidden();
    }
}
