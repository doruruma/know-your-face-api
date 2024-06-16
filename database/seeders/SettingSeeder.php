<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();
        $setting = new Setting;        
        $setting->office_address = 'Monas, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta';
        $setting->office_longitude = '-6.175174455799934';
        $setting->office_latitude = '106.82719414426197';
        $setting->max_distance = 10;
        $setting->save();
    }
}
