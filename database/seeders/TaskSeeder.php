<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        Task::insert([
            [
                'title' => 'abab görev',
                'creator_id' => 1,
                'start_date' => now(),
                'end_date' => now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'İkinci görev',
                'creator_id' => 1,
                'start_date' => now(),
                'end_date' => now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
