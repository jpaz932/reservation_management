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

    public function getAll()
    {
        return $this->reservation::where('status', '!=', 'cancelled')->get();
    }

    public function findById(int $id)
    {
        return $this->reservation->where([
            ['id', $id], 
            ['status', '!=', 'cancelled']
        ])->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->reservation->create($data);
    }

    public function update(array $data, int $id)
    {
        $reservation = $this->reservation->findOrFail($id);

        if (
            !$this->resourceRepository->checkAvailability(
                $data['resource_id'],
                $data['reserved_at'],
                $data['duration'],
                $id
            )
        ) {
            throw new \Exception('The resource is not available on the selected date  time.', 409);
        }

        $reservation->update($data);
        return $reservation;
    }

    public function cancel(int $id)
    {
        $reservation = $this->findById($id);
        $reservation->update(['status' => 'cancelled']);
        return $reservation;
    }
}