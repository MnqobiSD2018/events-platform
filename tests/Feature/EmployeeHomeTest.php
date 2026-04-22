<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_is_redirected_to_employee_home_from_home_route(): void
    {
        $employee = User::factory()->employee()->create();

        $this->actingAs($employee)
            ->get(route('home'))
            ->assertRedirect(route('employee.home'));
    }

    public function test_employee_can_view_home_summary_and_recent_updates(): void
    {
        $employee = User::factory()->employee()->create();

        Announcement::create([
            'title' => 'HR Policy Update',
            'body' => 'Remote work policy has been updated.',
            'category' => 'hr',
            'published_at' => now(),
            'created_by' => null,
        ]);

        Announcement::create([
            'title' => 'Company Sports Day',
            'body' => 'Join the annual team sports event this Friday.',
            'category' => 'company',
            'published_at' => now()->subMinute(),
            'created_by' => null,
        ]);

        $this->actingAs($employee)
            ->get(route('employee.home'))
            ->assertOk()
            ->assertSeeText('Unread Updates')
            ->assertSeeText('HR Policy Update')
            ->assertSeeText('Company Sports Day');
    }

    public function test_employee_can_filter_announcements_and_mark_as_read(): void
    {
        $employee = User::factory()->employee()->create();

        $hrAnnouncement = Announcement::create([
            'title' => 'Benefits Enrollment',
            'body' => 'Complete your benefits enrollment by month end.',
            'category' => 'hr',
            'published_at' => now(),
            'created_by' => null,
        ]);

        Announcement::create([
            'title' => 'Quarterly Financial Update',
            'body' => 'Company-wide numbers are now published.',
            'category' => 'company',
            'published_at' => now(),
            'created_by' => null,
        ]);

        $this->actingAs($employee)
            ->get(route('employee.announcements.index', ['category' => 'hr']))
            ->assertOk()
            ->assertSeeText('Benefits Enrollment')
            ->assertDontSeeText('Quarterly Financial Update');

        $this->actingAs($employee)
            ->post(route('employee.announcements.read', $hrAnnouncement))
            ->assertRedirect();

        $this->assertDatabaseHas('announcement_reads', [
            'user_id' => $employee->id,
            'announcement_id' => $hrAnnouncement->id,
        ]);
    }

    public function test_company_admin_cannot_access_employee_home(): void
    {
        $admin = User::factory()->createOne();

        $this->actingAs($admin)
            ->get(route('employee.home'))
            ->assertForbidden();
    }
}
