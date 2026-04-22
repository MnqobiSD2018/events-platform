<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user first
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
        Event::create([
        'name' => 'Annual Wellness Marathon',
        'description' => 'A 5K run to promote employee health',
        'type' => 'marathon',
        'event_date' => now()->addDays(30),
        'user_id' => 1, // assuming the first user exists
    ]);
    }
}
