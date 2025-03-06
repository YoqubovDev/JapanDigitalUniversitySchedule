<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GroupControllerTest extends TestCase
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
    public function test_can_create_group()
    {
        $response=$this->postJson("/api/groups",[
            'name'=>'Group 2232'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message'=>"Group created successfully"
            ]);
        $this->assertDatabaseHas('groups',['name'=>'Group 2232']);

    }

    public function test_cannot_create_group()
    {
        $response=$this->postJson("/api/groups",[
            'name'=>''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');


    }

    public function test_can_show_group()
    {
        $group=Group::factory()->create();
        $response=$this->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson(['id'=>$group->id, 'name'=>$group->name]);
    }

    public function test_can_update_group()
    {
        $group = Group::factory()->create();

        $response = $this->putJson("/api/groups/{$group->id}", [
            'name' => 'Updated Group',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Group updated successfully',
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
        ]);
    }



    public function test_can_delete_group()
    {
        $group=Group::factory()->create();
        $response=$this->deleteJson("/api/groups/{$group->id}");
        $response->assertStatus(200)
            ->assertJson([
                'message'=>'Group deleted successfully'
            ]);
        $this->assertDatabaseMissing('groups',['id' => $group->id]);

    }
}
