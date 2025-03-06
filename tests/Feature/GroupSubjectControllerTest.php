<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupSubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $subject;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = Subject::factory()->create();
        $this->token = $this->subject->createToken('test-token')->plainTextToken;
    }

    public function test_can_attach_subject_to_group()
    {
        $group = Group::factory()->create();
        $subject = Subject::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/group-subjects', [
                'group_id' => $group->id,
                'subject_id' => $subject->id,
            ]);
        $response->assertStatus(201)
            ->assertJson(['message' => 'Subject attached to group successfully']);
        $this->assertDatabaseHas('group_subject', [
            'group_id' => $group->id,
            'subject_id' => $subject->id,
        ]);
    }

    public function test_cannot_attach_to_nonexistent_group()
    {
        $subject = Subject::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/group-subjects', [
                'group_id' => 999,
                'subject_id' => $subject->id,
            ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id']);
    }

    public function test_validation_fails_on_store()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/group-subjects', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id', 'subject_id']);
    }

    public function test_can_detach_subject_from_group_via_update()
    {
        $group = Group::factory()->create();
        $group->subjects()->attach($this->subject->id);
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/group-subjects/{$group->id}", [
                'subject_id' => $this->subject->id,
            ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Subject update from group successfully']);
        $this->assertDatabaseMissing('group_subject', [
            'group_id' => $group->id,
            'subject_id' => $this->subject->id,
        ]);
    }

    public function test_cannot_detach_from_nonexistent_group_via_update()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson('/api/group-subjects/999', [
                'subject_id' => $this->subject->id,
            ]);
        $response->assertStatus(404);
    }

    public function test_validation_fails_on_update()
    {
        $group = Group::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/group-subjects/{$group->id}", []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject_id']);
    }

    public function test_can_detach_subject_from_group_via_destroy()
    {
        $group = Group::factory()->create();
        $subject = Subject::factory()->create();
        $group->subjects()->attach($subject->id);
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/group-subjects/{$subject->id}", [
                'group_id' => $group->id,
            ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Subject detached from group successfully']);
        $this->assertDatabaseMissing('group_subject', [  // Fixed table name
            'group_id' => $group->id,
            'subject_id' => $subject->id,
        ]);
    }
    public function test_cannot_detach_with_invalid_group_id_via_destroy()
    {
        $subject = Subject::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/group-subjects/{$subject->id}", [
                'group_id' => 999,
            ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id']);
    }

    public function test_validation_fails_on_destroy()
    {
        $subject = Subject::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/group-subjects/{$subject->id}", []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id']);
    }
}
