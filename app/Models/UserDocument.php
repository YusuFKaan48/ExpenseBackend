<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'file_path', 'document_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
