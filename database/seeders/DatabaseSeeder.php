<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PositionSeeder::class);
        $this->call(LeaveTypeSeeder::class);
        $this->call(WorkstateSeeder::class);
        $this->call(WorkHourSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(PresenceSeeder::class);
        $this->call(HolidaySeeder::class);
        $this->call(RemoteScheduleSeeder::class);
        $this->call(LeaveSeeder::class);
    }
}
