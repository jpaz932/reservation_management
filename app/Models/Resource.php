<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Resource",
 *     type="object",
 *     title="Recurso",
 *     description="Modelo que representa un recurso en el sistema de gestión de reservas.",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID único del recurso",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nombre del recurso",
 *         example="Sala de reuniones A"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Tipo de recurso (por ejemplo, sala de reuniones o equipo)",
 *         enum={"meeting_room", "equipment"},
 *         example="meeting_room"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descripción del recurso",
 *         example="Sala equipada con proyector y capacidad para 10 personas"
 *     ),
 *     @OA\Property(
 *         property="capacity",
 *         type="integer",
 *         description="Capacidad máxima del recurso",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         description="Indica si el recurso está activo",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de creación del recurso",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de la última actualización del recurso",
 *         example="2023-01-02T15:30:00Z"
 *     )
 * )
 */
class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'capacity',
        'is_active'
    ];

    public const TYPES = [
        'MEETING_ROOM' => 'meeting_room',
        'EQUIPMENT' => 'equipment',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
