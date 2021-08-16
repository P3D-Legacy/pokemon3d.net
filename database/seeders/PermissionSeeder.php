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

        $p1 = Permission::firstOrCreate(['name' => 'manage.users']);
        $p2 = Permission::firstOrCreate(['name' => 'api']);
        $p3 = Permission::firstOrCreate(['name' => 'blog-create']);
        $p4 = Permission::firstOrCreate(['name' => 'blog-update']);
        $p5 = Permission::firstOrCreate(['name' => 'blog-destroy']);

        $r1->givePermissionTo('manage.users');
        $r1->givePermissionTo('api');
        $r1->givePermissionTo($p3->name);
        $r1->givePermissionTo($p4->name);
        $r1->givePermissionTo($p5->name);

        $user = User::first();
        $user->assignRole($r1);

    }
}
