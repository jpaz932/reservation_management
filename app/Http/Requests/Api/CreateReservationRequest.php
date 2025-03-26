<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="CreateReservationRequest",
 *     type="object",
 *     title="Create Reservation Request",
 *     description="Esquema de validación para crear una reserva",
 *     required={"resource_id", "reserved_at", "duration"},
 *     @OA\Property(
 *         property="resource_id",
 *         type="integer",
 *         description="ID del recurso a reservar",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="reserved_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de la reserva en el formato d-m-Y H:i",
 *         example="25-12-2023 14:30"
 *     ),
 *     @OA\Property(
 *         property="duration",
 *         type="integer",
 *         description="Duración de la reserva en minutos, debe ser al menos 1",
 *         example=20
 *     )
 * )
 */
class CreateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resource_id' => 'required|integer|exists:resources,id',
            'reserved_at' => 'required|date|date_format:d-m-Y H:i',
            'duration' => 'required|integer|min:1',
        ];
    }
}
