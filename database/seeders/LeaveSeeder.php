<?php

namespace Database\Seeders;

use App\Helpers\Constant;
use App\Models\Leave;
use App\Models\LeaveDetail;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Leave::truncate();
        LeaveDetail::truncate();

        $db = new Leave;
        $db->leave_type_id = Constant::$LEAVE_ID;
        $db->workstate_id = Constant::$STATE_REQUESTED_ID;
        $db->user_id = 3;
        $db->notes = 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident fugit porro ex earum a asperiores saepe totam iure rerum! Perspiciatis vel error asperiores enim laudantium non ab libero quos optio!';
        $db->save();
        $detail = new LeaveDetail;
        $detail->leave_id = $db->id;
        $detail->leave_date = '2024-06-20';
        $detail->save();
        $detail = new LeaveDetail;
        $detail->leave_id = $db->id;
        $detail->leave_date = '2024-06-21';
        $detail->save();

        $db = new Leave;
        $db->leave_type_id = Constant::$LEAVE_ID;
        $db->workstate_id = Constant::$STATE_APPROVED_ID;
        $db->user_id = 4;
        $db->notes = 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident fugit porro ex earum a asperiores saepe totam iure rerum! Perspiciatis vel error asperiores enim laudantium non ab libero quos optio!';
        $db->save();
        $detail = new LeaveDetail;
        $detail->leave_id = $db->id;
        $detail->leave_date = '2024-06-10';
        $detail->save();
        $detail = new LeaveDetail;
        $detail->leave_id = $db->id;
        $detail->leave_date = '2024-06-11';
        $detail->save();
    }
}
