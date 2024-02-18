<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Project::create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'Belajar Laravel',
            'description' => 'Belajar Laravel dari dasar',
        ]);
    }
}
