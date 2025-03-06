<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

Class RoleUserControllerTest extends TestCase {
    use RefreshDatabase;

    public User $user;
    public Role $role;
    public string $token;
    public function setUp(): void{
        parent::setUp();
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
        $this->role = Role::create([
            'name' => 'Test Role',
        ]);
    }
    public function test_create_role_user () {
        $createRoleUser = $this->withHeaders(['Authorization'=>'Bearer ' . $this->token])
            ->postJson('/api/role-user', [
                'role_id' => $this->role->id,
                'user_id' => $this->user->id
            ]);
        $createRoleUser->assertStatus(200);
        $createRoleUser->assertJson([
            'success' => true,
        ]);
    }
    public function test_update_role_user () {
        $updateRoleUser = $this->withHeaders(['Authorization'=>'Bearer ' . $this->token])
            ->putJson('/api/role-user/' . $this->user->id, [
                'role_id' => $this->role->id,
                'user_id' => $this->user->id
            ]);
        $updateRoleUser->assertStatus(201);
        $updateRoleUser->assertJson([
            'success' => true,
        ]);
    }

    public function test_can_delete_role_user()
    {
        $deleteRoleUser = $this->withHeaders(['Authorization'=>'Bearer ' . $this->token])
            ->deleteJson('/api/role-user/' . $this->user->id, [
                    'role_id' => $this->role->id
                ]);
        $deleteRoleUser->assertStatus(200);
        $deleteRoleUser->assertJson([
            'success' => true,
        ]);
   }
}
