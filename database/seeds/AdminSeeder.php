<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\User;
        $admin->name = "Jack";
        $admin->email = "jack@gmail.com";
        $admin->password = \Hash::make("12345678");
        $admin->roles = json_encode(["PEMOHON"]);
        $admin->status = "ACTIVE";

        $admin->save();
        
        $this->command->info("Admin successfully inserted");
    }
}
