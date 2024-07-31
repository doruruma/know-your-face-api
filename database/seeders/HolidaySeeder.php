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
        $db->name = 'Hari Lahir Pancasila';
        $db->date = '2024-06-01';
        $db->save();

        $db = new Holiday;
        $db->name = 'Idul Adha';
        $db->date = '2024-06-17';
        $db->save();

        $db = new Holiday;
        $db->name = 'Cuti Bersama Idul Adha';
        $db->date = '2024-06-18';
        $db->save();

        $db = new Holiday;
        $db->name = 'Tahun Baru Islam 1446H';
        $db->date = '2024-07-07';
        $db->save();

        $db = new Holiday;
        $db->name = 'Hari Kemerdekaan Indonesia 79';
        $db->date = '2024-08-17';
        $db->save();
    }
}
