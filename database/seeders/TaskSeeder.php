<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Task::create([
            'id' => 1,
            'project_id' => 1,
            'title' => 'Task 1',
            'description' => 'Belajar migration laravel',
            'status' => 'pending',
        ]);
    }
}
