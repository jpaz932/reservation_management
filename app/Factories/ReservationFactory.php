<?php

namespace App\Factories;

use App\Models\Resource;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\ResourceRepository;
use Carbon\Carbon;
use InvalidArgumentException;

class ReservationFactory
{
    private $reservationRepository;
    private $resourceRepository;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        ResourceRepository $resourceRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->resourceRepository = $resourceRepository;
    }

    public function create(array $data)
    {
        $reservedAt = $data['reserved_at'];
        $duration = $data['duration'];
        $resourceId = $data['resource_id'];

        if (
            !$this->resourceRepository->checkAvailability(
                $resourceId,
                $reservedAt,
                $duration
            )
        ) {
            throw new \Exception('The resource is not available on the selected date  time.', 409);
        }

        $resource = $this->resourceRepository->findById($resourceId);

        switch ($resource->type) {
            case Resource::TYPES['MEETING_ROOM']:
                return $this->createMeetingRoomReservation($resource, $reservedAt, $duration);
            case Resource::TYPES['EQUIPMENT']:
                return $this->createEquipmentReservation($resource, $reservedAt, $duration);
            default:
                throw new InvalidArgumentException('Unsupported resource type: ' . $resource->type);
        }
    }

    private function createMeetingRoomReservation(Resource $resource, $reservedAt, $duration)
    {
        if ($duration > 240) {
            throw new InvalidArgumentException('Meeting rooms cannot be booked for more than 4 hours.');
        }

        return $this->createReservation($resource, $reservedAt, $duration);
    }

    private function createEquipmentReservation(Resource $resource, $reservedAt, $duration)
    {
        if ($duration > 480) {
            throw new InvalidArgumentException('Equipment cannot be reserved for more than 8 hours.');
        }

        return $this->createReservation($resource, $reservedAt, $duration);
    }

    private function createReservation(Resource $resource, $reservedAt, $duration, $status = null)
    {
        $data = [
            'resource_id' => $resource->id,
            'user_id' => auth()->id(),
            'reserved_at' => Carbon::parse($reservedAt),
            'duration' => $duration,
        ];

        return $this->reservationRepository->create($data);
    }
}