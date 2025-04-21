<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this->date)->format('d/m/Y');
        return [
            'id' => $this->id,
            'datetime' => "{$date} - {$this->time}",
            'number_of_people' => $this->number_of_people,
            'diner' => $this->diner->name,
            'table' => $this->table->number,
            'status' => $this->status,
        ];
    }
}
