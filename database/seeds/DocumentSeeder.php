<?php

use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('documents')->insert([
            ['name' => 'JSA'],
            ['name' => 'Sertifikat Peralatan A2B dan SIO Operator'],
            ['name' => 'PO/PPJ/KONTRAK/Memo Dinas'],
            ['name' => 'Daftar Peralatan'],
            ['name' => 'Daftar Pekerja'],
        ]);
    }
}
