<?php

namespace Tests\Feature;

use App\Models\ReserveMeeting;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReserveReverseSession extends TestCase
{

    public function test_reserve_session($reverse = false, $free_meetings = false)
    {
        $response = $this->get('contributors?' . ($free_meetings ? 'free_meetings=on' : ''));

        $response->assertStatus(200);

        // make sure the response contains the instructor and not 0

        $this->assertNotEmpty($response->getOriginalContent()->getData()['instructors']);
        $instructor = $response->getOriginalContent()->getData()['instructors'][0];

        $response = $this->get('users/' . $instructor->id . '/profile?tab=appointments' . ($reverse ? '&is_reverse=1' : ''));
        $response->assertStatus(200);


        $faker = \Faker\Factory::create();
        //find random student
        $student = User::where('role_name', 'user')->get()->random();
        $this->actingAs($student);
        $date = Carbon::now()->addDays(rand(1, 100));
        $availableTimes = $this->postJson('/users/'.$instructor->id.'/availableTimes',
        [
            'timestamp' => $date->getTimestamp(),
            'day_label' => $date->format('l'),
            'date' => $date->format('Y-m-d'),
        ]);
        $availableTimes->assertStatus(200);
        $availableTimes = json_decode($availableTimes->getContent());
        $reserveData = [
            'day' => $date->format('Y-m-d'),
            'time' => $faker->randomElement($availableTimes->times)->id,
            'meeting_type' => $faker->randomElement(['in_person', 'online']),
            'session_type' => $faker->randomElement(ReserveMeeting::$session_types),
            'student_count' => 1,
            'is_reverse' => $reverse,
        ];
        $beforeCreateTime = Carbon::now();
        $response = $this->post('meetings/reserve', $reserveData);
        $response->assertStatus(200);

        // Check in database
        $reverseMeetingModel = ReserveMeeting::where('day', $reserveData['day'])
            ->where('meeting_type', $reserveData['meeting_type'])
            ->where('session_type', $reserveData['session_type'])
            ->where('student_count', $reserveData['student_count'])
            ->where('mentoring_type', $reserveData['is_reverse'] ? 'reverse' : 'meeting')
            ->where('user_id', $student->id)
            ->where('created_at', '>=', $beforeCreateTime)
            ->first();
        $this->assertNotNull($reverseMeetingModel);

    }

    public function test_matrix()
    {
        $this->test_reserve_session(true, true);
        $this->test_reserve_session(true, false);
        $this->test_reserve_session(false, true);
        $this->test_reserve_session(false, false);
    }
}
