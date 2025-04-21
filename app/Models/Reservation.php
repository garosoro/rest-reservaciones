<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'number_of_people',
        'diner_id',
        'table_id',
    ];
    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];
    protected $with = [
        'diner',
        'table',
    ];

    public function diner(): BelongsTo
    {
        return $this->belongsTo(Diner::class);
    }
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
