<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GropMemberControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }

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
}
