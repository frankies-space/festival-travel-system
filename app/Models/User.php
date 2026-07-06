<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'points_balance', 'available_discount', 'vip_until', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points_balance' => 'integer',
            'available_discount' => 'decimal:2',
            'vip_until' => 'datetime',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function pointsTransactions(): HasMany
    {
        return $this->hasMany(PointsTransaction::class);
    }

    public function isVip(): bool
    {
        if ($this->points_balance >= config('fts.vip_threshold')) {
            return true;
        }

        return $this->vip_until !== null && $this->vip_until->isFuture();
    }

    public function hasActiveVipAccess(): bool
    {
        return $this->isVip();
    }

    public function canRedeemVip(): bool
    {
        if ($this->vip_until?->isFuture()) {
            return false;
        }

        if ($this->points_balance >= config('fts.vip_threshold')) {
            return false;
        }

        return $this->points_balance >= config('fts.vip_redeem_cost');
    }

    public function isPlanner(): bool
    {
        return $this->role === 'planner';
    }
}
