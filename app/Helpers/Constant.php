<?php

namespace App\Helpers;

class Constant
{
    // default pagination per page
    public static $PAGE_SIZE = 15;
    // default user profile
    public static $USER_PROFILE_IMAGE = 'default-profile.png';
    // default attachment
    public static $LEAVE_ATTACHMENT_IMAGE = 'default-attachment.png';
    // sick leave id
    public static $SICK_LEAVE_ID = 1;
    // default leave id
    public static $LEAVE_ID = 2;
    // workstate requested id
    public static $STATE_REQUESTED_ID = 1;
    // workstate approved id
    public static $STATE_APPROVED_ID = 2;
    // workstate rejected id
    public static $STATE_REJECTED_ID = 3;
    // workstate cancelled id
    public static $STATE_CANCELLED_ID = 4;
    // default clock in
    public static $CLOCK_IN_TIME = '08:00';
    // default clock out
    public static $CLOCK_OUT_TIME = '17:00';
    
    public static $MANAGEMENT_POSITION_ID = 2;
}
