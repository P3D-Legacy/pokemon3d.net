<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $r1 = Role::firstOrCreate(["name" => "super-admin"]);
        Role::firstOrCreate(["name" => "admin"]);

        Permission::firstOrCreate(['name' => 'manage.users']);
        Permission::firstOrCreate(['name' => 'api']);

        $r1->givePermissionTo('manage.users');
        $r1->givePermissionTo('api');

        $user = User::first();
        if($user) {
            $user->assignRole($r1);
        }

    }
}
