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
        $r2 = Role::firstOrCreate(["name" => "admin"]);

        $p1 = Permission::firstOrCreate(['name' => 'manage.users']);
        $p2 = Permission::firstOrCreate(['name' => 'manage.roles']);
        $p3 = Permission::firstOrCreate(['name' => 'manage.permissions']);

        $p4 = Permission::firstOrCreate(['name' => 'api']);

        $p5 = Permission::firstOrCreate(['name' => 'posts.create']);
        $p6 = Permission::firstOrCreate(['name' => 'posts.update']);
        $p7 = Permission::firstOrCreate(['name' => 'posts.destroy']);

        $p8 = Permission::firstOrCreate(['name' => 'tags.create']);
        $p9 = Permission::firstOrCreate(['name' => 'tags.update']);
        $p10 = Permission::firstOrCreate(['name' => 'tags.destroy']);

        // Super Admin permissions
        $r1->givePermissionTo($p1->name);
        $r1->givePermissionTo($p2->name);
        $r1->givePermissionTo($p3->name);
        $r1->givePermissionTo($p4->name);
        $r1->givePermissionTo($p5->name);
        $r1->givePermissionTo($p6->name);
        $r1->givePermissionTo($p7->name);
        $r1->givePermissionTo($p8->name);
        $r1->givePermissionTo($p9->name);
        $r1->givePermissionTo($p10->name);

        // Admin permissions
        $r2->givePermissionTo($p5->name);
        $r2->givePermissionTo($p6->name);
        $r2->givePermissionTo($p7->name);
        $r2->givePermissionTo($p8->name);
        $r2->givePermissionTo($p9->name);
        $r2->givePermissionTo($p10->name);

        $user = User::first();
        if($user) {
            $user->assignRole($r1);
        }

    }
}
