<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jobTitle extends Model
{
    protected $fillable = ['user_id', 'jobTitleName'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

