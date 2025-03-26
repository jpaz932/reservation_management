<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    protected $reservation;
    protected $resourceRepository;

    public function __construct(
        Reservation $reservation,
        ResourceRepositoryInterface $resourceRepository
    ) {
        $this->reservation = $reservation;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * Get all reservations
     * @return \Illuminate\Database\Eloquent\Collection<int, Reservation>
     */
    public function getAll()
    {
        return $this->reservation::where('status', '!=', 'cancelled')->get();
    }

    /**
     * Get a reservation by id
     * @param int $id
     * @return Reservation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id)
    {
        return $this->reservation->where([
            ['id', $id],
            ['status', '!=', 'cancelled']
        ])->firstOrFail()->load('resource', 'user:id,name,email');
    }

    /**
     * Create a new reservation
     * @param array $data
     * @return Reservation
     */
    public function create(array $data)
    {
        return $this->reservation->create($data);
    }

    /**
     * Update a reservation
     * @param array $data
     * @param int $id
     * @return Reservation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Exception if the resource is not available on the selected date time
     */
    public function update(array $data, int $id)
    {
        $reservation = $this->reservation->findOrFail($id);

        if (
            isset($data['resource_id'], $data['reserved_at'], $data['duration']) &&
            !$this->resourceRepository->checkAvailability(
                $data['resource_id'],
                $data['reserved_at'],
                $data['duration'],
                $id
            )
        ) {
            throw new \Exception('The resource is not available on the selected date time.', 409);
        }

        $reservation->update($data);
        return $reservation;
    }

    /**
     * Cancel a reservation
     * @param int $id
     * @return Reservation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function cancel(int $id)
    {
        $reservation = $this->findById($id);
        $reservation->update(['status' => 'cancelled']);
        return $reservation;
    }
}