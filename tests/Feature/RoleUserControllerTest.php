<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_role_to_user()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $response = $this->postJson('/api/roles-users', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Role attached to user']);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_update_user_role()
    {
        $user = User::factory()->create();
        $oldRole = Role::factory()->create();
        $newRole = Role::factory()->create();

        $user->roles()->attach($oldRole->id);

        $response = $this->putJson("/api/roles-users/{$user->id}", [
            'role_id' => $newRole->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'User role updated successfully']);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $newRole->id,
        ]);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $oldRole->id,
        ]);
    }

    /** @test */
    public function it_can_role_from_user()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user->roles()->attach($role->id);

        $response = $this->deleteJson("/api/roles-users/{$user->id}", [
            'role_id' => $role->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role detached from user']);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_returns_validation_error_if_user_or_role_does_not_exist()
    {
        $response = $this->postJson('/api/roles-users', [
            'user_id' => 999, // Bunday user yo'q
            'role_id' => 999, // Bunday role yo'q
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'role_id']);
    }
}
