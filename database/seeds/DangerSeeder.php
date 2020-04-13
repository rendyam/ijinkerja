<?php

use Illuminate\Database\Seeder;

class DangerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dangers')->insert([
            ['name' => 'Spark (Loncatan Api)'],
            ['name' => 'Panas'],
            ['name' => 'Debu'],
            ['name' => 'Bahan Kimia'],
            ['name' => 'Gas Berbahaya'],
            ['name' => 'Electrical Shock'],
            ['name' => 'Kebakaran'],
            ['name' => 'Kejatuhan Benda'],
            ['name' => 'Tertabrak'],
            ['name' => 'Terpotong'],
            ['name' => 'Terjatuh/Tersandung'],
        ]);
    }
}
