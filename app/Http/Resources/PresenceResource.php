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
            'is_late' => $this->is_late,
            'is_late_label' => $this->is_late == 1 ? 'Terlambat' : 'Tepat Waktu',
            'user' => new UserResource($this->whenLoaded('user')),
            'schedule_time_in' => $this->schedule_time_in,
            'schedule_time_out' => $this->schedule_time_out,
            'time_in' => Util::formatDateTime($this->time_in, 'H:i'),
            'time_out' => Util::formatDateTime($this->time_out, 'H:i'),
            'longitude_clock_in' => $this->longitude_clock_in,
            'longitude_clock_out' => $this->longitude_clock_out,
            'latitude_clock_in' => $this->latitude_clock_in,
            'latitude_clock_out' => $this->latitude_clock_out,
            'clock_in_distance' => $this->clock_in_distance,
            'clock_out_distance' => $this->clock_out_distance,
            'face_image_clock_in' => "storage/presences/$this->face_image_clock_in",
            'face_image_clock_out' => "storage/presences/$this->face_image_clock_out",
            'is_remote' => $this->is_remote,
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
