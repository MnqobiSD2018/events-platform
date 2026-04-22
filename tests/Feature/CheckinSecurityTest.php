<?php

namespace Tests\Feature;

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

    private function createParticipant(): Participant
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
            'email' => 'security@example.com',
            'department' => 'IT',
            'qr_code' => 'security-qr-code',
            'event_id' => $event->id,
        ]);
    }
}