<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Poblar la tabla regions
        DB::table('regions')->insert([
            [
                'id_reg' => 1,
                'description' => 'Mexico',
                'status' => 'A',
                'created_at' => '2023-09-18 22:31:32',
                'updated_at' => '2023-09-18 22:31:32',
            ],
            [
                'id_reg' => 2,
                'description' => 'Chile',
                'status' => 'A',
                'created_at' => '2023-09-18 22:31:32',
                'updated_at' => '2023-09-18 22:31:32',
            ],
        ]);
    }
}
