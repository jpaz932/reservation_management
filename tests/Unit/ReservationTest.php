<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationTest extends TestCase
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

    public function test_can_create_reservation()
    {
        $id = $this->resource->id;
        $response = $this->postJson('/api/reservations', [
            'resource_id' => $id,
            'reserved_at' => '01-01-2026 10:00',
            'duration' => 60,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'resource_id' => $id,
                'duration' => 60,
                'reserved_at' => '2026-01-01T10:00:00.000000Z',
            ]);

        $this->assertDatabaseHas('reservations', [
            'resource_id' => $id,
            'duration' => 60,
            'reserved_at' => '2026-01-01T10:00:00.000000Z',
        ]);
    }

    public function test_cannot_create_reservation_for_wrong_date_format()
    {
        Reservation::create([
            'resource_id' => $this->resource->id,
            'reserved_at' => now()->addHours(2),
            'user_id' => $this->user->id,
            'duration' => 120,
        ]);

        $conflictingReservation = [
            'resource_id' => $this->resource->id,
            'reserved_at' => now()->addHours(2)->toDateTimeString(),
            'duration' => 120
        ];

        $response = $this->postJson('/api/reservations', $conflictingReservation);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['reserved_at']);
    }

    public function test_cannot_create_reservation_for_unavailable_time()
    {
        $conflictingReservation = [
            'resource_id' => $this->resource->id,
            'reserved_at' => now()->addHours(2)->format('d-m-Y H:i'),
            'duration' => 120
        ];

        $response = $this->postJson('/api/reservations', $conflictingReservation);

        $response
            ->assertStatus(409)
            ->assertJsonFragment(['message' => 'The resource is not available on the selected date time.']);
    }

    public function test_cannot_reserve_beyond_resource_type_duration_limit()
    {
        $invalidReservation = [
            'resource_id' => $this->resource->id,
            'reserved_at' => now()->addDays(2)->format('d-m-Y H:i'),
            'duration' => 400
        ];

        $response = $this->postJson('/api/reservations', $invalidReservation);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'Meeting rooms cannot be booked for more than 4 hours.']);
    }

    public function test_can_cancel_reservation()
    {
        $response = $this->deleteJson("/api/reservations/{$this->reservation->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->reservation->id,
                'status' => 'cancelled',
            ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $this->reservation->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_cannot_cancel_non_existing_reservation()
    {
        $response = $this->deleteJson('/api/reservations/9999');

        $response->assertStatus(404);
    }

    public function test_can_get_all_reservations()
    {
        $response = $this->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_can_get_single_reservation()
    {
        $response = $this->getJson("/api/reservations/{$this->reservation->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->reservation->id,
                'resource_id' => $this->resource->id,
                'reserved_at' => $this->reservation->reserved_at,
                'duration' => $this->reservation->duration,
            ]);
    }

    public function test_cannot_get_non_existing_reservation()
    {
        $response = $this->getJson('/api/reservations/9999');

        $response->assertStatus(404);
    }

    public function test_can_update_reservation()
    {
        $response = $this->putJson("/api/reservations/{$this->reservation->id}", [
            'reserved_at' => '01-01-2026 12:00',
            'duration' => 90,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->reservation->id,
                'reserved_at' => '2026-01-01T12:00:00.000000Z',
                'duration' => 90,
            ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $this->reservation->id,
            'reserved_at' => '2026-01-01T12:00:00.000000Z',
            'duration' => 90,
        ]);
    }

    public function test_cannot_update_non_existing_reservation()
    {
        $response = $this->putJson('/api/reservations/9999', [
            'reserved_at' => '01-01-2026 12:00',
            'duration' => 90,
        ]);

        $response->assertStatus(404);
    }
}
