<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="UpdateResourceRequest",
 *     type="object",
 *     title="Update Resource Request",
 *     description="Esquema de validación para actualizar un recurso",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nombre del recurso",
 *         maxLength=255,
 *         example="Sala de reuniones A"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Tipo de recurso",
 *         enum={"meeting_room", "equipment"},
 *         example="meeting_room"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descripción del recurso",
 *         maxLength=255,
 *         nullable=true,
 *         example="Una sala equipada con proyector y pizarra"
 *     ),
 *     @OA\Property(
 *         property="capacity",
 *         type="integer",
 *         description="Capacidad del recurso",
 *         nullable=true,
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         description="Estado de activación del recurso",
 *         nullable=true,
 *         example=true
 *     )
 * )
 */
class UpdateResourceRequest extends FormRequest
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
            'name' => 'string|max:255',
            'type' => 'in:meeting_room,equipment',
            'description' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
    }
}
