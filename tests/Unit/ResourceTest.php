<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $resource;
    protected $reservation;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        $this->resource = Resource::factory()->create();

        $this->reservation = Reservation::create([
            'resource_id' => $this->resource->id,
            'reserved_at' => now()->addHours(2)->format('d-m-Y H:i'),
            'user_id' => $this->user->id,
            'duration' => 120,
        ]);
    }

    public function test_can_create_resource()
    {
        $resourceData = [
            'name' => 'Sala de Conferencias Principal',
            'description' => 'Sala grande para reuniones ejecutivas',
            'capacity' => 20,
            'type' => Resource::TYPES['MEETING_ROOM']
        ];

        $response = $this->postJson('/api/resources', $resourceData);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($resourceData);

        $this->assertDatabaseHas('resources', $resourceData);
    }

    public function test_cannot_create_resource_with_invalid_data()
    {
        $invalidResourceData = [
            'name' => '',
            'type' => 'invalid_type'
        ];

        $response = $this->postJson('/api/resources', $invalidResourceData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'type']);
    }

    public function test_can_get_all_resources()
    {
        Resource::factory()->count(5)->create();

        $response = $this->getJson('/api/resources');

        $response->assertStatus(200)
            ->assertJsonCount(6);
    }

    public function test_can_get_single_resource()
    {
        $resource = $this->resource;

        $response = $this->getJson("/api/resources/{$resource->id}");

        $response->assertStatus(200)
            ->assertJsonFragment($resource->toArray());
    }

    public function test_can_update_resource()
    {
        $resource = $this->resource;

        $updatedResourceData = [
            'name' => 'Sala de Conferencias Principal Actualizada',
            'description' => 'Sala grande para reuniones ejecutivas',
            'capacity' => 20,
            'type' => Resource::TYPES['MEETING_ROOM']
        ];

        $response = $this->putJson("/api/resources/{$resource->id}", $updatedResourceData);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($updatedResourceData);

        $this->assertDatabaseHas('resources', $updatedResourceData);
    }

    public function test_can_delete_resource()
    {
        $resource = $this->resource;

        $response = $this->deleteJson("/api/resources/{$resource->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'is_active' => 0
        ]);
    }

    public function test_cannot_delete_non_existing_resource()
    {
        $response = $this->deleteJson('/api/resources/999');

        $response->assertStatus(404);
    }

    public function test_can_get_resource_availability()
    {
        $resource = $this->resource;

        $response = $this->postJson("/api/resources/{$resource->id}/availability", [
            'reserved_at' => '01-01-2026 10:00',
            'duration' => 60,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true
            ]);
    }

    public function test_cannot_get_resource_availability_for_unavailable_time()
    {
        $resource = $this->resource;

        $response = $this->postJson("/api/resources/{$resource->id}/availability", [
            'reserved_at' => now()->addHours(2)->format('d-m-Y H:i'),
            'duration' => 120,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'available' => false
            ]);
    }
}
