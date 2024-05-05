<?php

namespace Database\Seeders;

use App\Helpers\Util;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        $arr = [
            [
                'position_id' => 1,
                'nik' => Util::generateNumber(16),
                'name' => 'Admin',
                'phone' => '085900829912',
                'gender' => 'F',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('P@ssw0rd?/'),
                'profile_image' => 'default-profile.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'position_id' => 2,
                'nik' => Util::generateNumber(16),
                'name' => 'Management',
                'phone' => '085900829912',
                'gender' => 'M',
                'email' => 'management@gmail.com',
                'password' => Hash::make('P@ssw0rd?/'),
                'profile_image' => 'default-profile.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        User::insert($arr);
    }
}
