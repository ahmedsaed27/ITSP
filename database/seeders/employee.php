<?php

namespace Database\Seeders;

use App\Models\Employees;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class employee extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employees::create([
            'name' => 'Hr',
            'email' => 'hr@hr.com',
            'phone' => '01123036886',
            'password' => bcrypt('password'),
            'departments_id' => 1,
            'position_type' => 'Hr',
            'address' => ['Al-Libini Haram Street'],
            'gander' => 1,
            'education' => 'Hr',
            'skils' => ['hr'],

        ]);
    }
}
