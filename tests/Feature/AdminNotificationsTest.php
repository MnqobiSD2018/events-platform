<?php

namespace Tests\Feature;

use App\Models\BroadcastNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_admin_can_send_notification_to_all_employees(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'user_type' => User::TYPE_COMPANY_ADMIN,
        ]);

        /** @var User $employeeA */
        $employeeA = User::factory()->create([
            'user_type' => User::TYPE_EMPLOYEE,
        ]);
        /** @var User $employeeB */
        $employeeB = User::factory()->create([
            'user_type' => User::TYPE_EMPLOYEE,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.notifications.store'), [
                'title' => 'Office Closed Friday',
                'message' => 'The office will be closed this Friday for maintenance.',
                'category' => 'hr',
            ]);

        $response->assertRedirect(route('admin.notifications.index'));

        $this->assertDatabaseHas('broadcast_notifications', [
            'title' => 'Office Closed Friday',
            'category' => 'hr',
            'recipient_count' => 2,
        ]);

        $broadcast = BroadcastNotification::query()->firstOrFail();

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $employeeA->id,
            'notifiable_type' => User::class,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $employeeB->id,
            'notifiable_type' => User::class,
        ]);

        $this->assertEquals(2, $broadcast->recipient_count);
    }

    public function test_employee_cannot_access_admin_notifications_panel(): void
    {
        /** @var User $employee */
        $employee = User::factory()->create([
            'user_type' => User::TYPE_EMPLOYEE,
        ]);

        $this->actingAs($employee)
            ->get(route('admin.notifications.index'))
            ->assertForbidden();
    }

    public function test_employee_can_mark_notification_as_read(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'user_type' => User::TYPE_COMPANY_ADMIN,
        ]);

        /** @var User $employee */
        $employee = User::factory()->create([
            'user_type' => User::TYPE_EMPLOYEE,
        ]);

        $this->actingAs($admin)->post(route('admin.notifications.store'), [
            'title' => 'Benefits Enrollment',
            'message' => 'Benefits enrollment opens next Monday.',
            'category' => 'policy',
        ]);

        $notification = $employee->fresh()->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $this->actingAs($employee)
            ->patch(route('employee.notifications.read', $notification->id))
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'notifiable_id' => $employee->id,
        ]);

        $this->assertNotNull($employee->fresh()->notifications()->first()->read_at);
    }
}
