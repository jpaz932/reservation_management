<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Carbon\Carbon;

class ResourceRepository implements ResourceRepositoryInterface
{
    protected $resource;
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function getAll()
    {
        return $this->resource::where('is_active', true)->get();
    }

    public function findById(int $id)
    {
        return $this->resource->where([
            ['id', $id], 
            ['is_active', true]
        ])->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->resource->create($data);
    }

    public function update(array $data, int $id)
    {
        $resource = $this->resource->findOrFail($id);
        $resource->update($data);
        return $resource;
    }

    public function delete($id)
    {
        $resource = $this->findById($id);
        $resource->update(['is_active' => false]);
        return $resource;
    }

    public function checkAvailability($resourceId, $reservedAt, $duration) 
    {
        $resource = $this->findById($resourceId);
        if (!$resource->is_active) {
            return false;
        }

        $startTime = Carbon::parse($reservedAt);
        $endTime = $startTime->copy()->addMinutes($duration);

        $conflictingReservations = $resource->reservations()
            ->where('status', '!=', 'cancelled')
            ->whereRaw('reserved_at <= ? AND DATE_ADD(reserved_at, INTERVAL duration MINUTE) > ?', [$endTime, $startTime])
            ->exists();

        return !$conflictingReservations;
    }
}
