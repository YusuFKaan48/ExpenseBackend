<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    public function run()
    {
        DB::table('data')->insert([
            ['title' => 'First try', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Second try', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Second try', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Second try', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Second try', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Second try', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
