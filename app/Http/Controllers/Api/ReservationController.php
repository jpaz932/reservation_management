<?php

namespace App\Http\Controllers\Api;

use App\Factories\ReservationFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateReservationRequest;
use App\Http\Requests\Api\UpdateReservationRequest;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

/**
 * @OA\Tag(
 *   name="Reservations",
 *   description="Endpoints de reservas",
 * )
 */
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

    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Obtener todas las reservas",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Reservation")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $reservations = $this->reservationRepository->getAll();
        return response()->json($reservations);
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Crear una reserva",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Error de conflicto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(CreateReservationRequest $request)
    {
        try {
            $reservation = $this->reservationFactory->create($request->validated());
            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/{id}",
     *     summary="Obtener una reserva",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $reservation = $this->reservationRepository->findById($id);
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/reservations/{id}",
     *     summary="Actualizar una reserva",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Error de conflicto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/reservations/{id}",
     *     summary="Cancelar una reserva",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva cancelada",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
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
