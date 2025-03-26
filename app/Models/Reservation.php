<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Reservation",
 *     type="object",
 *     title="Reservación",
 *     description="Modelo de reservación",
 *     required={"resource_id", "user_id", "reserved_at", "duration", "status"},
 *     @OA\Property(
 *         property="resource_id",
 *         type="integer",
 *         description="ID del recurso reservado"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID del usuario que hizo la reserva"
 *     ),
 *     @OA\Property(
 *         property="reserved_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de la reserva"
 *     ),
 *     @OA\Property(
 *         property="duration",
 *         type="integer",
 *         description="Duración de la reserva en minutos"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Estado de la reserva",
 *         enum={"confirmed", "pending", "cancelled"}
 *     )
 * )
 */
class Reservation extends Model
{
    protected $fillable = [
        'resource_id',
        'user_id',
        'reserved_at',
        'duration',
        'status',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
