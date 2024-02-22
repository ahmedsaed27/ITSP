<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Citys extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = public_path('eg.json');
        $jsonContents = file_get_contents($jsonPath);
        $cities = json_decode($jsonContents, true);

        foreach ($cities as $city) {
            DB::table('citys')->insert([
                'city' => $city['city'],
                'admin_name' => $city['admin_name'],
                'capital' => $city['capital'] ?? null,
            ]);
        }
    }
}
