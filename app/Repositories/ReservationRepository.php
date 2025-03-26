<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    protected $reservation;

    public function __construct(Reservation $reservation) 
    {
        $this->reservation = $reservation;
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