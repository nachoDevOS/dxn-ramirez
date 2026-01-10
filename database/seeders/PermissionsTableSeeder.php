<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'keyDescription'=>'vista de acceso al sistema',
            'table_name' => 'admin',
            'tableDescription'=>'Panel del Sistema'
        ]);

        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');
        Permission::generateFor('permissions');
        Permission::generateFor('settings');

        Permission::generateFor('users');



        $permissions = [
            'browse_cashiers' => 'Ver lista de cajas',
            'read_cashiers' => 'Ver detalles de cajas',
            'edit_cashiers' => 'Editar información de cajas',
            'add_cashiers' => 'Agregar nuevas cajas',
            'delete_cashiers' => 'Eliminar cajas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'cashiers',
                'tableDescription'=>'Cajas'
            ]);
        }

        $permissions = [
            'browse_sales' => 'Ver lista de ventas',
            'read_sales' => 'Ver detalles de ventas',
            'edit_sales' => 'Editar información de ventas',
            'add_sales' => 'Agregar nuevas ventas',
            'delete_sales' => 'Eliminar ventas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'sales',
                'tableDescription'=>'Ventas'
            ]);
        }


        

        // Citas
        $permissions = [
            'browse_appointments' => 'Ver lista de cita',
            'read_appointments' => 'Ver detalles de la cita',
            'edit_appointments' => 'Asignar personal a la cita',
            // 'edit_appointments' => 'Editar información de personas',
            // 'add_appointments' => 'Agregar nuevas personas',
            'delete_appointments' => 'Eliminar Citas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'appointments',
                'tableDescription'=>'Bandeja de Solicitudes de citas'
            ]);
        }

        // Administracion
        $permissions = [
            'browse_pets' => 'Ver lista de las mascotas',
            'read_pets' => 'Ver detalles de las mascotas',
            'edit_pets' => 'Editar información de las mascotas',
            'add_pets' => 'Agregar nuevas mascotas',
            'delete_pets' => 'Eliminar mascotas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'pets',
                'tableDescription'=>'Mascotas'
            ]);
        }

        $permissions = [
            'browse_workers' => 'Ver lista de trabajadores',
            'read_workers' => 'Ver detalles de un trabajador',
            'edit_workers' => 'Editar información de trabajadores',
            'add_workers' => 'Agregar nuevos trabajadores',
            'delete_workers' => 'Eliminar trabajadores',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'workers',
                'tableDescription'=>'Trabajadores'
            ]);
        }

        $permissions = [
            'browse_people' => 'Ver lista de personas',
            'read_people' => 'Ver detalles de una persona',
            'edit_people' => 'Editar información de personas',
            'add_people' => 'Agregar nuevas personas',
            'delete_people' => 'Eliminar personas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'people',
                'tableDescription'=>'Personas'
            ]);
        }

        $permissions = [
            'browse_incomes' => 'Ver lista de compras',
            'read_incomes' => 'Ver detalles de compras',
            'edit_incomes' => 'Editar información de compras',
            'add_incomes' => 'Agregar nuevas compras',
            'delete_incomes' => 'Eliminar compras',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'incomes',
                'tableDescription'=>'Compras'
            ]);
        }


        // Parametros
        // servicios
        $permissions = [
            'browse_services' => 'Ver lista de servicios',
            'read_services' => 'Ver detalles de los servicios',
            'edit_services' => 'Editar información de servicios',
            'add_services' => 'Agregar nuevos servicios',
            'delete_services' => 'Eliminar servicios',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'services',
                'tableDescription'=>'Servicios'
            ]);
        }

        // Animal
        $permissions = [
            'browse_animals' => 'Ver lista de animales',
            'read_animals' => 'Ver detalles de los animales',
            'edit_animals' => 'Editar información de animales',
            'add_animals' => 'Agregar nuevos animales',
            'delete_animals' => 'Eliminar animales',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'animals',
                'tableDescription'=>'Animales o Especies'
            ]);
        }

        $permissions = [
            'browse_races' => 'Ver lista de razas',
            'read_races' => 'Ver detalles de las razas',
            'edit_races' => 'Editar información de las razas',
            'add_races' => 'Agregar nuevas razas',
            'delete_races' => 'Eliminar razas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'races',
                'tableDescription'=>'Razas'
            ]);
        }



        // Parametros de Inventario
        $permissions = [
            'browse_categories' => 'Ver lista de categorias',
            'read_categories' => 'Ver detalles de categorias',
            'edit_categories' => 'Editar información de categorias',
            'add_categories' => 'Agregar nuevas categorias',
            'delete_categories' => 'Eliminar categorias',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'categories',
                'tableDescription'=>'Categorias'
            ]);
        }

        $permissions = [
            'browse_presentations' => 'Ver lista de presentaciones',
            'read_presentations' => 'Ver detalles de presentaciones',
            'edit_presentations' => 'Editar información de presentaciones',
            'add_presentations' => 'Agregar nuevas presentaciones',
            'delete_presentations' => 'Eliminar presentaciones',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'presentations',
                'tableDescription'=>'Presentaciones'
            ]);
        }

        $permissions = [
            'browse_suppliers' => 'Ver lista de proveedores',
            'read_suppliers' => 'Ver detalles de proveedores',
            'edit_suppliers' => 'Editar información de proveedores',
            'add_suppliers' => 'Agregar nuevos proveedores',
            'delete_suppliers' => 'Eliminar proveedores',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'suppliers',
                'tableDescription'=>'Proveedores'
            ]);
        }

        $permissions = [
            'browse_laboratories' => 'Ver lista de laboratorio',
            'read_laboratories' => 'Ver detalles de los laboratorio',
            'edit_laboratories' => 'Editar información de laboratorio',
            'add_laboratories' => 'Agregar nuevos laboratorio',
            'delete_laboratories' => 'Eliminar laboratorio',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'laboratories',
                'tableDescription'=>'Laboratorio'
            ]);
        }

        $permissions = [
            'browse_brands' => 'Ver lista de marcas',
            'read_brands' => 'Ver detalles de marcas',
            'edit_brands' => 'Editar información de marcas',
            'add_brands' => 'Agregar nuevas marcas',
            'delete_brands' => 'Eliminar marcas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'brands',
                'tableDescription'=>'Marcas'
            ]);
        }

        $permissions = [
            'browse_items' => 'Ver lista de productos o items',
            'read_items' => 'Ver detalles de productos o items',
            'edit_items' => 'Editar información de productos o items',
            'add_items' => 'Agregar nuevos productos o items',
            'delete_items' => 'Eliminar productos o items',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'items',
                'tableDescription'=>'Productos o Items'
            ]);
        }


     



        
        
    }
}