<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{

    public function run(): void
    {
        $perms = [
            'govcontrol.view',
            'govcontrol.create',
            'govcontrol.update',
            'govcontrol.delete',
            'govcontrol.reopen',
        ];


        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $moderator = Role::firstOrCreate(['name' => 'moderator']);
        $user      = Role::firstOrCreate(['name' => 'user']);

        $moderator->syncPermissions($perms);

        $user->syncPermissions(['govcontrol.view']);
    }
}
