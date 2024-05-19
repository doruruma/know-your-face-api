<?php

namespace App\Http\Resources;

use App\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
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
            'user_id' => $this->user_id,
            'schedule_time_in' => $this->schedule_time_in,
            'schedule_time_out' => $this->schedule_time_out,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'longitude_clock_in' => $this->longitude_clock_in,
            'longitude_clock_out' => $this->longitude_clock_out,
            'latitude_clock_in' => $this->latitude_clock_in,
            'latitude_clock_out' => $this->latitude_clock_out,
            'face_image_clock_in' => $this->face_image_clock_in,
            'face_image_clock_out' => $this->face_image_clock_out,
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
