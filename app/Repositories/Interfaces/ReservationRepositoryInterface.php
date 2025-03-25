<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function checkAvailability(int $resourceId, string $reservedAt, int $duration): bool;
    public function cancel(Reservation $reservation);
}
