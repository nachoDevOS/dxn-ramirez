<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('services')->delete();
        
        \DB::table('services')->insert(array (
            0 => 
            array (
                'id' => 1,
                'icon' => '<i class="fa-solid fa-user-doctor"></i>',
                'name' => 'CONSULTAS VETERINARIAS',
                'observation' => 'Exámenes de salud completos, diagnóstico y tratamiento para mantener a tu mascota saludable.',
                'status' => 1,
                'created_at' => '2025-12-01 00:45:03',
                'updated_at' => '2025-12-04 00:35:57',
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
                'icon' => '<i class="fa-solid fa-scissors"></i>',
                'name' => 'BAÑO Y PELUQUERIA',
                'observation' => 'Cuidado estético integral para el bienestar y salud de tu mascota. Servicios profesionales de higiene y estética canina y felina adaptados a las necesidades específicas de cada animal, realizados por especialistas certificados.',
                'status' => 1,
                'created_at' => '2025-12-01 00:45:26',
                'updated_at' => '2025-12-04 00:39:30',
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
                'icon' => '<i class="fas fa-syringe"></i>',
                'name' => 'VACUNA Y DESPARACITACION',
                'observation' => 'Programas de vacunación personalizados para proteger a tu mascota de enfermedades comunes.',
                'status' => 1,
                'created_at' => '2025-12-01 00:46:05',
                'updated_at' => '2025-12-04 00:31:21',
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
                'icon' => '<i class="fas fa-cut"></i>',
                'name' => 'CIRUGIAS',
                'observation' => 'Procedimientos quirúrgicos con equipos de última generación y anestesia segura.',
                'status' => 1,
                'created_at' => '2025-12-01 00:46:24',
                'updated_at' => '2025-12-04 00:30:36',
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
                'icon' => '<i class="fa-solid fa-hand-holding-medical"></i>',
                'name' => 'URGENCIAS',
                'observation' => 'Atención médica inmediata las 24 horas para salvar vidas. Servicio de emergencia veterinaria disponible todos los días del año, con equipo especializado y tecnología avanzada para manejar situaciones críticas que amenazan la vida de tu mascota.',
                'status' => 1,
                'created_at' => '2025-12-01 00:46:34',
                'updated_at' => '2025-12-04 10:04:00',
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