<?php

namespace App\Http\Resources;

use App\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
            'formatted_date' => Util::formatDateTime($this->date, 'd-m-Y'),
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
