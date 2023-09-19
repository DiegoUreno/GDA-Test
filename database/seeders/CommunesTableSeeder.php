<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Poblar la tabla communes
        DB::table('communes')->insert([
            [
                'id_com' => 1,
                'id_reg' => 1,
                'description' => '',
                'status' => 'A',
                'created_at' => '2023-09-18 22:31:32',
                'updated_at' => '2023-09-18 22:31:32',
            ],
            [
                'id_com' => 2,
                'id_reg' => 2,
                'description' => 'Santiago',
                'status' => 'A',
                'created_at' => '2023-09-18 22:31:32',
                'updated_at' => '2023-09-18 22:31:32',
            ],
        ]);
    }
}
