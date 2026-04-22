<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultCloudUsersSeeder extends Seeder
{
    /**
     * Seed the application's default cloud users.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sys@sys.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Nd@19770'),
                'email_verified_at' => now(),
                'user_type' => User::TYPE_COMPANY_ADMIN,
                'team' => 'System',
                'department' => 'Administration',
                'employee_role' => 'platform_admin',
                'privacy_settings' => [
                    'share_with_colleagues' => false,
                    'show_department' => true,
                ],
            ]
        );

        User::updateOrCreate(
            ['email' => 'olivia.smith0@example.com'],
            [
                'name' => 'Olivia Smith',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'user_type' => User::TYPE_EMPLOYEE,
                'team' => 'Wellness Squad',
                'department' => 'Operations',
                'employee_role' => 'participant',
                'privacy_settings' => [
                    'share_with_colleagues' => true,
                    'show_department' => true,
                ],
            ]
        );
    }
}
