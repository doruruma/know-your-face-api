<?php

namespace App\Http\Resources;

use App\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkHourResource extends JsonResource
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
            'time_start' => Util::formatDateTime($this->time_start),
            'time_end' => Util::formatDateTime($this->time_end),
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
