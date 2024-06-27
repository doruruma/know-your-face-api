<?php

namespace Database\Seeders;

use App\Models\RemoteSchedule;
use Illuminate\Database\Seeder;

class RemoteScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RemoteSchedule::truncate();
        
        $db = new RemoteSchedule;
        $db->user_id = 3;
        $db->date = '2024-06-20';
        $db->save();

        $db = new RemoteSchedule;
        $db->user_id = 3;
        $db->date = '2024-06-21';
        $db->save();

        $db = new RemoteSchedule;
        $db->user_id = 3;
        $db->date = '2024-06-22';
        $db->save();

        $db = new RemoteSchedule;
        $db->user_id = 4;
        $db->date = '2024-06-29';
        $db->save();
    }
}
