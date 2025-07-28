<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    protected $fillable = ['user_id', 'start_time', 'end_time', 'duration_seconds'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
