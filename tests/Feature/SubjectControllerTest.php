<?php

namespace Tests\Feature;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }


    public function test_can_create_subject()
    {
        $response = $this->postJson('/api/subjects', [
            'name' => 'Biology'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Subject created successfully']);

        $this->assertDatabaseHas('subjects', ['name' => 'Biology']);
    }

    public function test_cannot_create_subject_with_invalid_data()
    {
        $response = $this->postJson('/api/subjects', [
            'name' => '' // Noto‘g‘ri data
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->getJson("/api/subjects/{$subject->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $subject->id, 'name' => $subject->name]);
    }

    public function test_can_update_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->putJson("/api/subjects/{$subject->id}", [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Subject updated successfully']);

        $this->assertDatabaseHas('subjects', ['id' => $subject->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->deleteJson("/api/subjects/{$subject->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Subject deleted successfully']);

        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }

}
