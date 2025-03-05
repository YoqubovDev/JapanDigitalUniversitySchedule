<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }
    /**
     * A basic feature test example.
     */
    public function test_can_create_room()
    {
        $response = $this->postJson('/api/rooms', [
            'name' => 'Room 1'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message'=>'Room created successfully'
            ]);
        $this->assertDatabaseHas('rooms',['name' => 'Room 1'
            ]);
   }

    public function test_cannot_create_room_with_invalid_data()
    {
        $response=$this->postJson('/api/rooms',[
            'name' => ''
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

   }

    public function test_can_show_room()
    {
        $room=Room::factory()->create();
        $response=$this->getJson("/api/rooms/{$room->id}");
        $response->assertStatus(200)
            ->assertJson(['id'=>$room->id, 'name'=>$room->name]);
   }

    public function test_can_update_room()
    {
        $room=Room::factory()->create();
        $response=$this->putJson("/api/rooms/{$room->id}",[
            'name' => 'Updated Room'
        ]);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Room updated successfully'
            ]);
        $this->assertDatabaseHas('rooms',['id' => $room->id,'name' => 'Updated Room']);
   }

    public function test_cannot_update_room_with_invalid_data()
    {
        $room=Room::factory()->create();
        $response=$this->putJson("/api/rooms/{$room->id}",[
            'name' => ''
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

   }

    public function test_can_delete_room()
    {
        $room=Room::factory()->create();
        $response=$this->deleteJson("/api/rooms/{$room->id}");
        $response->assertStatus(200)
            ->assertJson([
                'message'=>'Room deleted successfully'
            ]);
        $this->assertDatabaseMissing('rooms',['id' => $room->id]);
   }
}
