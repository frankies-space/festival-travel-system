<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'festival_id',
        'bus_trip_id',
        'status',
        'price',
        'booked_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'booked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class);
    }

    public function busTrip(): BelongsTo
    {
        return $this->belongsTo(BusTrip::class);
    }
}
