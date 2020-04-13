<?php

use Illuminate\Database\Seeder;

class SafetyEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('safety_equipments')->insert([
            ['name' => 'Helmet'],
            ['name' => 'Safety Shoes'],
            ['name' => 'Sarung Tangan'],
            ['name' => 'Kacamata Safety'],
            ['name' => 'Masker'],
            ['name' => 'Pelindung Muka'],
            ['name' => 'Pelindung Telinga'],
            ['name' => 'Full Bodyharness'],
            ['name' => 'Life Jacket & Ring Buoy'],
            ['name' => 'Alat Bantu Pernapasan'],
        ]);
    }
}
