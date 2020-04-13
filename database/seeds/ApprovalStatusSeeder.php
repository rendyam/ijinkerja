<?php

use Illuminate\Database\Seeder;

class ApprovalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('approval_status')->insert([
            ['name' => 'Draft'],
            ['name' => 'Diajukan'],
            ['name' => 'Disetujui'],
            ['name' => 'Ditolak'],
        ]);
    }
}


