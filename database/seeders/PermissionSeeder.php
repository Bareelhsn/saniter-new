<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleTeknisi = Role::create(['name' => 'Teknisi']);
        $roleStaff = Role::create(['name' => 'Staff']);

        // $view = Permission::create(['name' => 'view']);
        // $create = Permission::create(['name' => 'create']);
        // $update = Permission::create(['name' => 'update']);
        // $delete = Permission::create(['name' => 'delete']);

        // $roleAdmin->givePermissionTo($view, $create, $update, $delete);
        // $roleTeknisi->givePermissionTo($view, $update);
        // $roleStaff->givePermissionTo($view, $create, $update);

        // $user = User::find(1);
        // $user->assignRole(['Admin']);
    }
}
