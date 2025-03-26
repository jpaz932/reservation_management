<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function cancel(int $id);
    public function update(array $data, int $id);
}
