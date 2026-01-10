<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PresentationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('presentations')->delete();
        
        \DB::table('presentations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'pza',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:05:58',
                'updated_at' => '2025-12-04 11:05:58',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'fco',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:06:09',
                'updated_at' => '2025-12-04 11:06:09',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Bolsa',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:06:19',
                'updated_at' => '2025-12-04 11:06:19',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'ml',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-19 03:36:19',
                'updated_at' => '2025-12-19 03:36:26',
                'registerUser_id' => 1,
                'registerRole' => 'administrador',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'kg',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-19 03:36:34',
                'updated_at' => '2025-12-19 03:36:34',
                'registerUser_id' => 1,
                'registerRole' => 'administrador',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
        ));
        
        
    }
}