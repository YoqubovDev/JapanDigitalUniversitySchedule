<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }
    /**
     */
    public function test_can_create_schedule()
    {
        $subject=Subject::factory()->create();
        $teacher=User::factory()->create();
        $group=Group::factory()->create();
        $room=Room::factory()->create();

        $response=$this->postJson("/api/schedules",[
            'subject_id'=>$subject->id,
            'teacher_id'=>$teacher->id,
            'group_id'=>$group->id,
            'room_id'=>$room->id,
            'pair'=>1,
            'week_day'=>'tuesday',
            'date'=>'2023-01-01'
        ]);

        $data = [
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'group_id' => $group->id,
            'room_id' => $room->id,
            'pair' => 1,
            'week_day' => 'tuesday',
            'date' => '2023-01-01',
        ];

        $response->assertStatus(200)
            ->assertJson([
                'message'=>'Schedule created successfully'
            ]);
        $this->assertDatabaseHas("schedules",$data);

    }

    public function test_cannot_create_schedule()
    {
        $subject=Subject::factory()->create();
        $teacher=User::factory()->create();
        $group=Group::factory()->create();
        $room=Room::factory()->create();

        $response=$this->postJson("/api/schedules",[
            'subject_id'=>$subject->id,
            'teacher_id'=>$teacher->id,
            'group_id'=>$group->id,
            'room_id'=>$room->id,
            'pair'=>1,
            'week_day'=>'',
            'date'=>'2023-01-01'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The week day field is required.',
                'errors' => [
                    'week_day' => ['The week day field is required.']
                ]
            ]);


    }


    public function test_can_update_schedule()
    {
        // Avval mavjud jadval yaratamiz
        $schedule = Schedule::factory()->create([
            'subject_id' => Subject::factory()->create()->id,
            'teacher_id' => User::factory()->create()->id,
            'group_id' => Group::factory()->create()->id,
            'room_id' => Room::factory()->create()->id,
            'pair' => 1,
            'week_day' => 'monday',
            'date' => '2023-06-01'
        ]);

        // Yangilash uchun yangi ma'lumotlar
        $newSubject = Subject::factory()->create();
        $newTeacher = User::factory()->create();
        $newGroup = Group::factory()->create();
        $newRoom = Room::factory()->create();

        $response = $this->putJson("/api/schedules/{$schedule->id}", [
            'subject_id' => $newSubject->id, // Yangilanayotgan ID'lar
            'teacher_id' => $newTeacher->id,
            'group_id' => $newGroup->id,
            'room_id' => $newRoom->id,
            'pair' => 2,
            'week_day' => 'friday',
            'date' => '2023-06-15'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Schedule updated successfully'
            ]);

        // **Eski ma'lumot endi bazada bo‘lmasligi kerak**
        $this->assertDatabaseMissing("schedules", [
            'id' => $schedule->id,
            'pair' => 1, // Eski qiymat
            'week_day' => 'monday', // Eski qiymat
            'date' => '2023-06-01' // Eski qiymat
        ]);

        // **Yangi ma'lumot bazada borligini tekshiramiz**
        $this->assertDatabaseHas("schedules", [
            'id' => $schedule->id,
            'subject_id' => $newSubject->id, // Yangilangan qiymatlar
            'teacher_id' => $newTeacher->id,
            'group_id' => $newGroup->id,
            'room_id' => $newRoom->id,
            'pair' => 2,
            'week_day' => 'friday',
            'date' => '2023-06-15'
        ]);
    }

    public function test_can_show_schedule()
    {
        $schedule = Schedule::factory()->create();
        $response=$this->getJson("/api/schedules/{$schedule->id}");

        $response->assertStatus(200)
            ->assertJson(['id'=>$schedule->id]);

    }

    public function test_can_delete_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response=$this->deleteJson("/api/schedules/{$schedule->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message'=>"Schedule deleted successfully"
            ]);
        $this->assertDatabaseMissing('schedules',['id' => $schedule->id]);

    }

}
