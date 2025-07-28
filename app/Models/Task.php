<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'creator_id'];

    public function assignees()
    {
        return $this->belongsToMany(User::class)->withPivot('is_completed')->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}

