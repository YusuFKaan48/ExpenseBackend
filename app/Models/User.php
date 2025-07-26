<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'addressName',
        'relative_name',
        'relative_number',
        'jobTitle',
        'id_number',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function IdCard()
    {
        return $this->hasOne(IdCard::class);
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

}

