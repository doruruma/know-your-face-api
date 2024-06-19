<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Holiday::truncate();

        $db = new Holiday;
        $db->name = 'Idul Adha';
        $db->date = '2024-06-17';
        $db->save();

        $db = new Holiday;
        $db->name = 'Libur fiktif 1';
        $db->date = '2024-06-01';
        $db->save();

        $db = new Holiday;
        $db->name = 'Libur fiktif 2';
        $db->date = '2024-06-05';
        $db->save();
    }
}
