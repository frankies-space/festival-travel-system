<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusTrip extends Model
{
    protected $fillable = [
        'festival_id',
        'departure_date',
        'departure_location',
        'max_passengers',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'max_passengers' => 'integer',
        ];
    }

    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
