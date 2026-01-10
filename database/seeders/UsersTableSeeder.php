<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@soluciondigital.dev',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$dAgeq5dRhm2UblYq81.VtetxG.zuEwQSlgObtGlin/0/GItYt0pIK',
                'remember_token' => 'tIT1W5OjuWiAhmG8COc5lPowCIMYMEdR3ej28LeaNk8w0q2WRCORSPw4uU7U',
                'settings' => '{"locale":"es"}',
                'created_at' => '2024-10-18 10:28:45',
                'updated_at' => '2025-12-08 15:47:19',
                'status' => 1,
                'person_id' => NULL,
                'registerUser_id' => NULL,
                'registerRole' => NULL,
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
        ));
        
        
    }
}