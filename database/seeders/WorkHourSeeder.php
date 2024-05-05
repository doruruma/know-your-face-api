<?php

namespace Database\Seeders;

use App\Models\WorkHour;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkHour::truncate();
        $arr = [
            [
                'name' => 'Reguler (08:00 s/d 17:00)',
                'time_start' => '08:00:00',
                'time_end' => '17:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Agak siang (09:00 s/d 18:00)',
                'time_start' => '09:00:00',
                'time_end' => '17:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Siang ke malam (12:00 s/d 20:00)',
                'time_start' => '12:00:00',
                'time_end' => '20:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        WorkHour::insert($arr);
    }
}
