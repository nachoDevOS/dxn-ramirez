<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Accesorios',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:02:40',
                'updated_at' => '2025-12-04 11:02:40',
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
                'name' => 'Aminoacidos',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:02:51',
                'updated_at' => '2025-12-04 11:02:51',
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
                'name' => 'Antiparasitario',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:03:02',
                'updated_at' => '2025-12-04 11:03:02',
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
                'name' => 'Talco',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:03:15',
                'updated_at' => '2025-12-04 11:03:15',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Jabon canino',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:03:28',
                'updated_at' => '2025-12-04 11:03:28',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Anestesia local',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:03:36',
                'updated_at' => '2025-12-04 11:03:36',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Antiflamatorio',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:03:43',
                'updated_at' => '2025-12-04 11:03:43',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Antibiotico',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:03:50',
                'updated_at' => '2025-12-04 11:03:50',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Anestesia gral',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:04:01',
                'updated_at' => '2025-12-04 11:04:01',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Anticonceptivo',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:04:09',
                'updated_at' => '2025-12-04 11:04:09',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Shampoo',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:04:17',
                'updated_at' => '2025-12-04 11:04:17',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Prueba rapida parvovirus',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:04:27',
                'updated_at' => '2025-12-04 11:04:27',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Prueba rapida moquillo',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:04:38',
                'updated_at' => '2025-12-04 11:04:38',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Alimentos',
                'observation' => NULL,
                'status' => 1,
                'created_at' => '2025-12-04 11:04:46',
                'updated_at' => '2025-12-04 11:04:46',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
        ));
        
        
    }
}