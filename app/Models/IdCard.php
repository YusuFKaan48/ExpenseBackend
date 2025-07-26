<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdCard extends Model
{
    protected $fillable = ['user_id', 'document_number'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
