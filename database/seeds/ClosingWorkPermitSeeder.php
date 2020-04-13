<?php

use Illuminate\Database\Seeder;

class ClosingWorkPermitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('closing_work_permits')->insert([
            ['name' => 'Jumlah Personel Lengkap'],
            ['name' => 'Seluruh Pakaian Dirapikan'],
            ['name' => 'Kebersihan']
        ]);
    }
}
