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

        $arr = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'type' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'employee',
                'email' => 'employee@admin.com',
                'password' => bcrypt('password'),
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'hr',
                'email' => 'hr@admin.com',
                'password' => bcrypt('password'),
                'type' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'modirator',
                'email' => 'modirator@admin.com',
                'password' => bcrypt('password'),
                'type' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach($arr as $array){
            ModelsUser::create($array);
        }

        // ModelsUser::insert(
        //     [
        //         [
        //             'name' => 'admin',
        //             'email' => 'admin@admin.com',
        //             'password' => bcrypt('password'),
        //             'type' => 0,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ],
        //         [
        //             'name' => 'employee',
        //             'email' => 'employee@admin.com',
        //             'password' => bcrypt('password'),
        //             'type' => 1,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ],
        //         [
        //             'name' => 'hr',
        //             'email' => 'hr@admin.com',
        //             'password' => bcrypt('password'),
        //             'type' => 2,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ],
        //         [
        //             'name' => 'modirator',
        //             'email' => 'modirator@admin.com',
        //             'password' => bcrypt('password'),
        //             'type' => 3,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]
        //     ]
        // );
    }
}
