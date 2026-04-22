<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'user_type' => User::TYPE_COMPANY_ADMIN,
            'team' => null,
            'department' => null,
            'employee_role' => null,
            'privacy_settings' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => User::TYPE_EMPLOYEE,
            'team' => 'Wellness Squad',
            'department' => 'Operations',
            'employee_role' => 'participant',
            'privacy_settings' => [
                'share_with_colleagues' => true,
                'show_department' => true,
            ],
        ]);
    }
}
