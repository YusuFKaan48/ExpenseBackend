<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run()
    {
        DB::table('tasks')->insert([
            ['title' => 'abab görev', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'İkinci görev', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
