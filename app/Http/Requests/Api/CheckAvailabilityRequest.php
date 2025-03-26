<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="CheckAvailabilityRequest",
 *     type="object",
 *     title="Check Availability Request",
 *     description="Solicitud para verificar la disponibilidad de una reserva",
 *     required={"reserved_at", "duration"},
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
 *         format="int32",
 *         description="Duración de la reserva en minutos, debe ser al menos 1",
 *         example=20
 *     )
 * )
 */
class CheckAvailabilityRequest extends FormRequest
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
            'reserved_at' => 'required|date|date_format:d-m-Y H:i',
            'duration' => 'required|integer|min:1',
        ];
    }
}
