<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permission_role')->delete();
        
        // Root
        $role = Role::where('name', 'admin')->firstOrFail();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions->pluck('id')->all());



        $role = Role::where('name', 'administrador')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "cashiers" or
                                            table_name = "sales" or

                                            table_name = "appointments" or
                                            table_name = "pets" or
                                            table_name = "people" or
                                            table_name = "incomes" or
                                            table_name = "workers" or

                                            table_name = "services" or
                                            table_name = "animals" or
                                            table_name = "races" or

                                            table_name = "categories" or
                                            table_name = "presentations" or
                                            table_name = "suppliers" or
                                            table_name = "laboratories" or
                                            table_name = "brands" or
                                            table_name = "items" or


                                            table_name = "people" or
                                            table_name = "roles" or
                                            table_name = "users" or
                                            table_name = "settings" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}
