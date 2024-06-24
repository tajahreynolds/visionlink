<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('point')->insert([
            ['name' => 'A', 'x' => 0, 'y' => 1],
            ['name' => 'B', 'x' => 1, 'y' => 0],
            ['name' => 'C', 'x' => 1, 'y' => 1],
            ['name' => 'D', 'x' => 2, 'y' => 2],
            ['name' => 'E', 'x' => 3, 'y' => 3],
        ]);
    }
}
