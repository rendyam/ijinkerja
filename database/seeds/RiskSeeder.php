<?php

use Illuminate\Database\Seeder;

class RiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('risks')->insert([
            ['name' => 'Ketinggian'],
            ['name' => 'Kerja Panas'],
            ['name' => 'Penggalian'],
            ['name' => 'Area Terbatas'],
            ['name' => 'Pekerjaan Bawah Air']
        ]);
    }
}
