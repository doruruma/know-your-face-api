<?php

namespace Database\Seeders;

use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::truncate();
        $arr = [
            [
                'name' => 'Admin',
                'description' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Management',
                'description' => 'Management',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Frontend Developer',
                'description' => 'Frontend Developer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Backend Developer',
                'description' => 'Backend Developer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'SQA',
                'description' => 'Software Quality Assurance',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Devops Engineer',
                'description' => 'Devops Engineer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        Position::insert($arr);
    }
}
