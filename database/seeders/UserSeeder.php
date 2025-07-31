<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'kaan',
            'email' => 'kaan@mail.com',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'name' => 'eren',
            'email' => 'eren@mail.com',
            'password' => Hash::make('123'),
        ]);
    }
}
