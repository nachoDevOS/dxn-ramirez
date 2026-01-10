<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2024-10-17 14:28:26',
                'updated_at' => '2025-04-06 16:18:35',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2024-10-17 14:28:26',
                'updated_at' => '2024-10-17 14:28:26',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Role',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2024-10-17 14:28:26',
                'updated_at' => '2024-10-17 14:28:26',
            ),
            3 => 
            array (
                'id' => 8,
                'name' => 'people',
                'slug' => 'people',
                'display_name_singular' => 'Persona',
                'display_name_plural' => 'Personas',
                'icon' => 'fa-solid fa-person',
                'model_name' => 'App\\Models\\Person',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-04-06 09:43:00',
                'updated_at' => '2026-01-05 22:10:24',
            ),
            4 => 
            array (
                'id' => 11,
                'name' => 'animals',
                'slug' => 'animals',
                'display_name_singular' => 'Especie',
                'display_name_plural' => 'Especies',
                'icon' => 'voyager-paw',
                'model_name' => 'App\\Models\\Animal',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-11-30 15:32:06',
                'updated_at' => '2025-12-01 16:34:09',
            ),
            5 => 
            array (
                'id' => 13,
                'name' => 'services',
                'slug' => 'services',
                'display_name_singular' => 'Servicio',
                'display_name_plural' => 'Servicios',
                'icon' => 'fas fa-stethoscope',
                'model_name' => 'App\\Models\\Service',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-11-30 15:48:02',
                'updated_at' => '2025-12-03 12:29:34',
            ),
            6 => 
            array (
                'id' => 16,
                'name' => 'suppliers',
                'slug' => 'suppliers',
                'display_name_singular' => 'Proveedor',
                'display_name_plural' => 'Proveedores',
                'icon' => 'voyager-ship',
                'model_name' => 'App\\Models\\Supplier',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-12-01 10:51:18',
                'updated_at' => '2025-12-01 10:55:12',
            ),
            7 => 
            array (
                'id' => 17,
                'name' => 'items',
                'slug' => 'items',
                'display_name_singular' => 'Producto / Item',
                'display_name_plural' => 'Productos / Items',
                'icon' => 'fa-brands fa-steam-symbol',
                'model_name' => 'App\\Models\\Item',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-12-01 11:13:15',
                'updated_at' => '2026-01-10 09:33:38',
            ),
            8 => 
            array (
                'id' => 18,
                'name' => 'categories',
                'slug' => 'categories',
                'display_name_singular' => 'Categoría',
                'display_name_plural' => 'Categorías',
                'icon' => 'fa-solid fa-layer-group',
                'model_name' => 'App\\Models\\Category',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-12-03 14:57:21',
                'updated_at' => '2025-12-03 14:58:06',
            ),
            9 => 
            array (
                'id' => 19,
                'name' => 'presentations',
                'slug' => 'presentations',
                'display_name_singular' => 'Presentación / Unidad de Medida',
                'display_name_plural' => 'Presentaciones / Unidad Medidas',
                'icon' => 'voyager-ticket',
                'model_name' => 'App\\Models\\Presentation',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2025-12-03 15:01:29',
                'updated_at' => '2025-12-03 15:01:29',
            ),
            10 => 
            array (
                'id' => 21,
                'name' => 'workers',
                'slug' => 'workers',
                'display_name_singular' => 'Trabajador',
                'display_name_plural' => 'Trabajadores',
                'icon' => 'fa-solid fa-user-doctor',
                'model_name' => 'App\\Models\\Worker',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-12-07 11:24:10',
                'updated_at' => '2026-01-05 22:10:11',
            ),
        ));
        
        
    }
}