<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::truncate();
        $arr = [
            [
                'name' => 'Sakit',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Cuti',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        LeaveType::insert($arr);
    }
}
