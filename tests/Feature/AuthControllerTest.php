<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $email = fake()->unique()->safeEmail(); // Har safar yangi email

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['user'],
                'token'
            ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jo', // Juda qisqa
            'email' => 'invalid-email', // Email noto'g'ri
            'password' => '12345', // Juda qisqa
            'password_confirmation' => '67890' // Teskari parol
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $existingUser = User::factory()->create();

        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => $existingUser->email, // Allaqachon mavjud email
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => fake()->unique()->safeEmail(), // Har safar yangi email
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user'
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password')]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'The provided credentials are incorrect']);
    }

    public function test_user_cannot_login_with_non_existent_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'The provided credentials are incorrect']);
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'User logged out.']);
    }
}
