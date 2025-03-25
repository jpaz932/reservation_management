<?php

namespace App\Repositories\Interfaces;

interface ResourceRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
    public function checkAvailability(int $resourceId, string $reservedAt, int $duration);
}
