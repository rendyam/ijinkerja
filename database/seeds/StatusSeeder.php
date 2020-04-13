<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_permit_status')->insert([
            ['name' => 'Draft'],
            ['name' => 'Diajukan'],
            ['name' => 'Disetujui'],
            ['name' => 'Ditolak'],
            ['name' => 'Menunggu Persetujuan Pemohon'],
            ['name' => 'Menunggu Persetujuan Safety Officer'],
            ['name' => 'Menunggu Persetujuan Kadis K3LH'],
            ['name' => 'Diterbitkan'],
            ['name' => 'Diperpanjang'],
            ['name' => 'Ditutup']
        ]);
    }
}
