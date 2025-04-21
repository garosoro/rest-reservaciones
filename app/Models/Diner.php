<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diner extends Model
{
    /** @use HasFactory<\Database\Factories\DinerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'telephone',
        'address',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
