<?php

namespace App\Http\Controllers\Api;

use App\Factories\ReservationFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateReservationRequest;
use App\Http\Requests\Api\UpdateReservationRequest;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationController extends Controller
{
    private $reservationRepository;
    private $reservationFactory;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        ReservationFactory $reservationFactory
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->reservationFactory = $reservationFactory;
    }

    public function index()
    {
        $reservations = $this->reservationRepository->getAll();
        return response()->json($reservations);
    }

    public function store(CreateReservationRequest $request)
    {
        try {
            $reservation = $this->reservationFactory->create($request->validated());
            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }

    public function show(int $id)
    {
        try {
            $reservation = $this->reservationRepository->findById($id);
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    }

    public function update(UpdateReservationRequest $request, int $id)
    {
        try {
            $reservation = $this->reservationRepository->update($request->validated(), $id);
            return response()->json($reservation);
        } catch (\Exception $e) {
            if ($e->getCode() === 409) {
                return response()->json(['message' => $e->getMessage()], 409);
            }
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $reservation = $this->reservationRepository->cancel($id);
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    }
}
