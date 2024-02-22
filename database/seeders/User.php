<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsUser::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'type' => 0
        ]);
    }
}
