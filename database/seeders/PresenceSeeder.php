<?php

namespace Database\Seeders;

use App\Models\Presence;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Presence::truncate();

        // $db = new Presence;
        // $db->user_id = 3;
        // $db->schedule_time_in = '08:00:00';
        // $db->schedule_time_out = '17:00:00';
        // $db->time_in = '08:00:00';
        // $db->time_out = '17:00:00';
        // $db->longitude_clock_in = '-6.175174455799934';
        // $db->latitude_clock_in = '106.82719414426197';
        // $db->longitude_clock_out = '-6.175174455799934';
        // $db->latitude_clock_out = '106.82719414426197';
        // $db->face_image_clock_in = 'default-profile.png';
        // $db->face_image_clock_out = 'default-profile.png';
        // $db->is_remote = 1;
        // $db->save();

        // $db = new Presence;
        // $db->user_id = 4;
        // $db->schedule_time_in = '08:00:00';
        // $db->schedule_time_out = '17:00:00';
        // $db->time_in = '08:00:00';
        // $db->time_out = '17:00:00';
        // $db->longitude_clock_in = '-6.175174455799934';
        // $db->latitude_clock_in = '106.82719414426197';
        // $db->longitude_clock_out = '-6.175174455799934';
        // $db->latitude_clock_out = '106.82719414426197';
        // $db->face_image_clock_in = 'default-profile.png';
        // $db->face_image_clock_out = 'default-profile.png';
        // $db->clock_in_distance = "1 Meter";
        // $db->clock_out_distance = "3 Meter";
        // $db->save();
    }
}
