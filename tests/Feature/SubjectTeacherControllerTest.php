<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupMemberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_be_attached_to_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson('/api/group-members', [
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Student attached to group successfully']);

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_student_can_be_detached_from_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->students()->attach($user->id);

        $response = $this->deleteJson("/api/group-members/{$user->id}", [
            'group_id' => $group->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Student  detached from group successfully']);

        $this->assertDatabaseMissing('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_teacher_can_be_attached_to_subject()
    {
        $subject = Subject::factory()->create();
        $teacher = User::factory()->create();

        $response = $this->postJson('/api/subject-teachers', [
            'subject_id' => $subject->id,
            'user_id' => $teacher->id,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Subject Teacher Added']);

        $this->assertDatabaseHas('subject_user', [
            'subject_id' => $subject->id,
            'user_id' => $teacher->id,
        ]);
    }

    public function test_teacher_can_be_detached_from_subject()
    {
        $subject = Subject::factory()->create();
        $teacher = User::factory()->create();
        $teacher->subjects()->attach($subject->id);

        $response = $this->deleteJson("/api/subject-teachers/{$teacher->id}", [
            'subject_id' => $subject->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Teacher detached from group successfully']);

        $this->assertDatabaseMissing('subject_user', [
            'subject_id' => $subject->id,
            'user_id' => $teacher->id,
        ]);
    }
}
