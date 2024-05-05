<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
