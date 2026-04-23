<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackerLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_home_shows_leaderboard_and_current_rank(): void
    {
        /** @var User $employeeA */
        $employeeA = User::factory()->employee()->create([
            'name' => 'Alicia Walker',
        ]);

        /** @var User $employeeB */
        $employeeB = User::factory()->employee()->create([
            'name' => 'Brian Runner',
        ]);

        ActivityLog::create([
            'user_id' => $employeeA->id,
            'activity_date' => now()->toDateString(),
            'workout_type' => 'walk',
            'steps' => 8400,
            'runs' => 0,
            'distance_km' => 6.8,
            'duration_minutes' => 60,
            'source' => 'imported',
            'provider' => 'fitbit',
            'notes' => null,
            'raw_payload' => null,
        ]);

        ActivityLog::create([
            'user_id' => $employeeB->id,
            'activity_date' => now()->toDateString(),
            'workout_type' => 'run',
            'steps' => 12350,
            'runs' => 1,
            'distance_km' => 10.2,
            'duration_minutes' => 54,
            'source' => 'imported',
            'provider' => 'garmin',
            'notes' => null,
            'raw_payload' => null,
        ]);

        $this->actingAs($employeeB)
            ->get(route('employee.home'))
            ->assertOk()
            ->assertSeeText('Top Step Movers')
            ->assertSeeText('Brian Runner')
            ->assertSeeText('Alicia Walker')
            ->assertSeeText('#1')
            ->assertSeeText('Your Rank');
    }

    public function test_admin_dashboard_shows_employee_leaderboard(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'user_type' => User::TYPE_COMPANY_ADMIN,
        ]);

        /** @var User $employeeA */
        $employeeA = User::factory()->employee()->create([
            'name' => 'Alicia Walker',
        ]);

        /** @var User $employeeB */
        $employeeB = User::factory()->employee()->create([
            'name' => 'Brian Runner',
        ]);

        ActivityLog::create([
            'user_id' => $employeeA->id,
            'activity_date' => now()->toDateString(),
            'workout_type' => 'walk',
            'steps' => 8400,
            'runs' => 0,
            'distance_km' => 6.8,
            'duration_minutes' => 60,
            'source' => 'imported',
            'provider' => 'fitbit',
            'notes' => null,
            'raw_payload' => null,
        ]);

        ActivityLog::create([
            'user_id' => $employeeB->id,
            'activity_date' => now()->toDateString(),
            'workout_type' => 'run',
            'steps' => 12350,
            'runs' => 1,
            'distance_km' => 10.2,
            'duration_minutes' => 54,
            'source' => 'imported',
            'provider' => 'garmin',
            'notes' => null,
            'raw_payload' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Employee Step Leaderboard')
            ->assertSeeText('Brian Runner')
            ->assertSeeText('Alicia Walker')
            ->assertSeeText('12,350')
            ->assertSeeText('8,400');
    }
}
