<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    protected $fillable = ['user_id', 'RelativeName', 'RelativeNumber'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
