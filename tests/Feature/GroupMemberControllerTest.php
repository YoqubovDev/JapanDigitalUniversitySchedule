<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupMemberControllerTest extends TestCase
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
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/group-members', [
                'group_id' => $group->id,
                'user_id' => $user->id,
            ]);
        $response->assertStatus(201)
            ->assertJson(['message' => 'Student attached to group successfully']);
        $this->assertDatabaseHas('group_student', [
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_attach_to_nonexistent_group()
    {
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/group-members', [
                'group_id' => 999,
                'user_id' => $user->id,
            ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id']);
    }

    public function test_validation_fails_on_store()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/group-members', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id', 'user_id']);
    }

    public function test_can_detach_user_from_group_via_update()
    {
        $group = Group::factory()->create();
        $group->users()->attach($this->user->id);
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/group-members/{$group->id}", [
                'user_id' => $this->user->id,
            ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Student update from group successfully']);
        $this->assertDatabaseMissing('group_student', [
            'group_id' => $group->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_cannot_detach_from_nonexistent_group_via_update()
    {
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson('/api/group-members/999', [
                'user_id' => $this->user->id,
            ]);
        $response->assertStatus(404);
    }

    public function test_validation_fails_on_update()
    {
        $group = Group::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/group-members/{$group->id}", []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_can_detach_user_from_group_via_destroy()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->users()->attach($user->id);
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/group-members/{$user->id}", [
                'group_id' => $group->id,
            ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Student detached from group successfully']);
        $this->assertDatabaseMissing('group_student', [
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_detach_with_invalid_group_id_via_destroy()
    {
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/group-members/{$user->id}", [
                'group_id' => 999,
            ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id']);
    }

    public function test_validation_fails_on_destroy()
    {
        $user = User::factory()->create();
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/group-members/{$user->id}", []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_id']);
    }
}
