<?php

namespace Database\Seeders;

use App\Models\Workstate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workstate::truncate();
        $arr = [
            [
                'name' => 'Diajukan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Diterima',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Ditolak',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Dibatalkan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        Workstate::insert($arr);
    }
}
