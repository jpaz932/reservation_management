<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="CreateResourceRequest",
 *     type="object",
 *     title="Create Resource Request",
 *     description="Esquema de validación para la creación de un recurso",
 *     required={"name", "type"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nombre del recurso",
 *         example="Sala de reuniones A"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Tipo de recurso. Valores permitidos: meeting_room, equipment",
 *         enum={"meeting_room", "equipment"},
 *         example="meeting_room"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descripción del recurso (opcional)",
 *         example="Una sala de reuniones equipada con proyector"
 *     ),
 *     @OA\Property(
 *         property="capacity",
 *         type="integer",
 *         description="Capacidad del recurso (opcional)",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         description="Estado del recurso, si está activo o no (opcional)",
 *         example=true
 *     )
 * )
 */
class CreateResourceRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:meeting_room,equipment',
            'description' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
    }
}
