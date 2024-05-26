<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'office_address' => $this->office_address,
            'office_longitude' => $this->office_longitude,
            'office_latitude' => $this->office_latitude,
            'max_distance' => $this->max_distance
        ];
    }
}
