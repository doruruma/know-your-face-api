<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleXUser extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
