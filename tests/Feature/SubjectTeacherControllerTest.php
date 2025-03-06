<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_attach_user_to_group()
    {
        $subject = Subject::factory()->create();
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/subject-teachers', [
                'subject_id' => $subject->id,
                'user_id' => $user->id,
            ]);
        $response->assertStatus(201)
            ->assertJson(['message' => 'Teacher attached to subject successfully']);
        $this->assertDatabaseHas('subject_teacher', [
            'subject_id' => $subject->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_attach_to_nonexistent_group()
    {
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/subject-teachers', [
                'subject_id' => 999,
                'user_id' => $user->id,
            ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject_id']);
    }

    public function test_validation_fails_on_store()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/subject-teachers', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject_id', 'user_id']);
    }

    public function test_can_detach_user_from_group_via_update()
    {
        $subject = Subject::factory()->create();
        $subject->teachers()->attach($this->user->id);  // Use teachers() instead of users()
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/subject-teachers/{$subject->id}", [
                'user_id' => $this->user->id,
            ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Teacher update from subject successfully']);
        $this->assertDatabaseMissing('subject_teacher', [
            'subject_id' => $subject->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_cannot_detach_from_nonexistent_group_via_update()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson('/api/subject-teachers/999', [
                'user_id' => $this->user->id,
            ]);
        $response->assertStatus(404);
    }

    public function test_validation_fails_on_update()
    {
        $subject = Subject::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/subject-teachers/{$subject->id}", []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_can_detach_user_from_group_via_destroy()
    {
        $subject = Subject::factory()->create();
        $user = User::factory()->create();
        $subject->teachers()->attach($user->id);  // Use teachers() instead of users()
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/subject-teachers/{$user->id}", [
                'subject_id' => $subject->id,
            ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Teacher detached from subject successfully']);
        $this->assertDatabaseMissing('subject_teacher', [
            'subject_id' => $subject->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_detach_with_invalid_group_id_via_destroy()
    {
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/subject-teachers/{$user->id}", [
                'subject_id' => 999,
            ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject_id']);
    }

    public function test_validation_fails_on_destroy()
    {
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/subject-teachers/{$user->id}", []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject_id']);
    }
}
