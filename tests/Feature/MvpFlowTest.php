<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class MvpFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_mvp_flow_from_registration_to_report(): void
    {
        $registerResponse = $this->post('/register', [
            'name' => 'Flow User',
            'email' => 'flow@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $registerResponse->assertRedirect(route('home', absolute: false));

        $registeredUser = User::where('email', 'flow@example.com')->firstOrFail();
        $registeredUser->update(['user_type' => User::TYPE_COMPANY_ADMIN]);

        $this->post('/logout')->assertRedirect('/');

        $loginResponse = $this->post('/login', [
            'email' => 'flow@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $loginResponse->assertRedirect(route('home', absolute: false));

        $eventPayload = [
            'name' => 'Quarterly Wellness',
            'description' => 'Q2 company wellness initiative',
            'type' => 'wellness program',
            'event_date' => now()->addDay()->toDateTimeString(),
        ];

        $this->post(route('events.store'), $eventPayload)
            ->assertRedirect(route('events.index'));

        $event = Event::where('name', 'Quarterly Wellness')->firstOrFail();

        $excelTempPath = storage_path('framework/cache/laravel-excel-tests/'.Str::uuid());
        File::deleteDirectory($excelTempPath);
        config(['excel.temporary_files.local_path' => $excelTempPath]);

        $csv = UploadedFile::fake()->createWithContent(
            'participants.csv',
            "name,email,department\nJane Doe,jane@example.com,HR\nJohn Smith,john@example.com,IT\n"
        );

        $this->post(route('events.import.csv', $event), [
            'csv_file' => $csv,
        ])->assertRedirect(route('events.show', $event));

        $this->assertDatabaseHas('participants', [
            'event_id' => $event->id,
            'email' => 'jane@example.com',
            'department' => 'HR',
        ]);

        $this->assertDatabaseHas('participants', [
            'event_id' => $event->id,
            'email' => 'john@example.com',
            'department' => 'IT',
        ]);

        $participant = Participant::where('email', 'jane@example.com')->firstOrFail();
        $signedCheckinUrl = URL::signedRoute('checkin.scan', ['participant' => $participant->id]);

        $this->getJson($signedCheckinUrl)
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Checked in successfully!',
            ]);

        $this->assertTrue(
            Attendance::where('event_id', $event->id)
                ->where('participant_id', $participant->id)
                ->whereNotNull('checked_in_at')
                ->exists()
        );

        $this->getJson($signedCheckinUrl)
            ->assertOk()
            ->assertJson([
                'success' => false,
                'message' => 'Already checked in.',
            ]);

        $reportResponse = $this->get(route('events.report', $event));

        $reportResponse->assertOk();
        $reportResponse->assertSeeText('Total Participants');
        $reportResponse->assertSeeText('Checked In');
        $reportResponse->assertSeeText('Attendance Rate');
        $reportResponse->assertSeeText('50%');
        $reportResponse->assertSeeText('Jane Doe');
        $reportResponse->assertSeeText('john@example.com');
        $reportResponse->assertSeeText('Not checked in');
    }
}