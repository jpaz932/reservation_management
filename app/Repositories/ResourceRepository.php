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

    /**
     * Get all resources
     * @return \Illuminate\Database\Eloquent\Collection<int, Resource>
     */
    public function getAll()
    {
        return $this->resource::where('is_active', true)->get();
    }

    /**
     * Find a resource by id
     * @param int $id
     * @return Resource
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id)
    {
        return $this->resource->where([
            ['id', $id], 
            ['is_active', true]
        ])->firstOrFail();
    }

    /**
     * Create a new resource
     * @param array $data
     * @return Resource
     */
    public function create(array $data)
    {
        return $this->resource->create($data);
    }

    /**
     * Summary of update
     * @param array $data
     * @param int $id
     * @return Resource
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(array $data, int $id)
    {
        $resource = $this->resource->findOrFail($id);
        $resource->update($data);
        return $resource;
    }

    /**
     * Inactivate a resource
     * @param int $id
     * @return Resource
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id)
    {
        $resource = $this->findById($id);
        $resource->update(['is_active' => false]);
        return $resource;
    }

    /**
     * Check if a resource is available for reservation
     * @param int $resourceId
     * @param string $reservedAt
     * @param int $duration
     * @param int|null $reservationId
     * @return bool
     */
    public function checkAvailability(int $resourceId, string $reservedAt, int $duration, int | null $reservationId = null) 
    {
        $resource = $this->findById($resourceId);
        if (!$resource->is_active) {
            return false;
        }

        $startTime = Carbon::parse($reservedAt);
        $endTime = $startTime->copy()->addMinutes($duration);

        $conflictingReservations = $resource->reservations()
            ->where('status', '!=', 'cancelled')
            ->whereRaw('reserved_at <= ? AND DATE_ADD(reserved_at, INTERVAL duration MINUTE) >= ?', [$endTime, $startTime])
            ->where('id', '!=', $reservationId)
            ->exists();

        return !$conflictingReservations;
    }
}
