<?php

namespace Database\Seeders;

use App\Models\Departments as ModelsDepartments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class department extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsDepartments::create([
            'name' => 'Hr',
            'description' => 'Hr',
        ]);
    }
}
