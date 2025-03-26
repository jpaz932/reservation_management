<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="UpdateReservationRequest",
 *     type="object",
 *     title="UpdateReservationRequest",
 *     description="Esquema de validación para actualizar una reserva",
 *     required={"status", "resource_id", "reserved_at", "duration"},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"confirmed"},
 *         description="Estado de la reserva. Solo se permite el valor 'confirmed'."
 *     ),
 *     @OA\Property(
 *         property="resource_id",
 *         type="integer",
 *         description="ID del recurso asociado a la reserva. Debe existir en la tabla 'resources'."
 *     ),
 *     @OA\Property(
 *         property="reserved_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de la reserva en el formato 'd-m-Y H:i'."
 *     ),
 *     @OA\Property(
 *         property="duration",
 *         type="integer",
 *         minimum=1,
 *         description="Duración de la reserva en minutos. Debe ser al menos 1."
 *     )
 * )
 */
class UpdateReservationRequest extends FormRequest
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
            'status' => 'in:confirmed',
            'resource_id' => 'integer|exists:resources,id',
            'reserved_at' => 'date|date_format:d-m-Y H:i',
            'duration' => 'integer|min:1',
        ];
    }
}
