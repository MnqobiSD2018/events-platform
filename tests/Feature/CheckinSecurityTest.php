<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CheckinSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unsigned_checkin_url_is_rejected(): void
    {
        $participant = $this->createParticipant();

        $this->get(route('checkin.scan', $participant))
            ->assertForbidden();
    }

    public function test_tampered_signed_checkin_url_is_rejected(): void
    {
        $participant = $this->createParticipant();
        $signedUrl = URL::signedRoute('checkin.scan', ['participant' => $participant->id]);

        $this->get($signedUrl.'&tamper=1')
            ->assertForbidden();
    }

    public function test_signed_url_with_invalid_participant_returns_not_found(): void
    {
        $signedUrl = URL::signedRoute('checkin.scan', ['participant' => 999999]);

        $this->get($signedUrl)
            ->assertNotFound();
    }

    public function test_signed_url_with_malformed_participant_identifier_returns_not_found(): void
    {
        $signedUrl = URL::signedRoute('checkin.scan', ['participant' => 'not-a-valid-participant-id']);

        $this->get($signedUrl)
            ->assertNotFound();
    }

    public function test_signed_checkin_creates_employee_profile_when_missing(): void
    {
        $participant = $this->createParticipant('new.employee@example.com');

        $this->assertDatabaseMissing('users', [
            'email' => 'new.employee@example.com',
        ]);

        $signedUrl = URL::signedRoute('checkin.scan', ['participant' => $participant->id]);

        $this->get($signedUrl)->assertOk();

        $this->assertDatabaseHas('users', [
            'email' => 'new.employee@example.com',
            'user_type' => User::TYPE_EMPLOYEE,
        ]);
    }

    public function test_logged_in_employee_scanning_their_own_qr_is_redirected_home(): void
    {
        $participantEmail = 'scanner.employee@example.com';
        $participant = $this->createParticipant($participantEmail);

        $employee = User::factory()->employee()->create([
            'email' => $participantEmail,
        ]);

        $signedUrl = URL::signedRoute('checkin.scan', ['participant' => $participant->id]);

        $this->actingAs($employee)
            ->get($signedUrl)
            ->assertRedirect(route('home'));

        $this->assertTrue(
            Attendance::where('participant_id', $participant->id)
                ->where('event_id', $participant->event_id)
                ->whereNotNull('checked_in_at')
                ->exists()
        );
    }

    private function createParticipant(string $email = 'security@example.com'): Participant
    {
        $user = User::factory()->create();

        $event = Event::create([
            'name' => 'Security Event',
            'description' => 'Security route checks',
            'type' => 'training',
            'event_date' => now()->addDay(),
            'user_id' => $user->id,
        ]);

        return Participant::create([
            'name' => 'Security Participant',
            'email' => $email,
            'department' => 'IT',
            'qr_code' => 'security-qr-code',
            'event_id' => $event->id,
        ]);
    }
}