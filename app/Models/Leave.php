<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function leaveDetails()
    {
        return $this->hasMany(LeaveDetail::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
