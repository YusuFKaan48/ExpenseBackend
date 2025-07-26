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
            'relative_name' => 'MockName',
            'relative_number' => '05551234567',
            'addressName' => 'MockAddress',
            'jobTitle' => 'MockJobTitle',
        ]);

        User::create([
            'name' => 'eren',
            'email' => 'eren@mail.com',
            'password' => Hash::make('123'),
            'relative_name' => 'MockName',
            'relative_number' => '05551234567',
            'addressName' => 'MockAddress2',
            'jobTitle' => 'MockJobTitle2',
        ]);
    }
}
