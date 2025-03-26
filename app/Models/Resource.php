<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
