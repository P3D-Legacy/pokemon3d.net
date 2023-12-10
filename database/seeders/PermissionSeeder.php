<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $super_admin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $moderator = Role::firstOrCreate(['name' => 'moderator']);

        Permission::firstOrCreate(['name' => 'manage.users']);
        Permission::firstOrCreate(['name' => 'manage.roles']);
        Permission::firstOrCreate(['name' => 'manage.permissions']);

        Permission::firstOrCreate(['name' => 'api']);

        Permission::firstOrCreate(['name' => 'post.show']);
        Permission::firstOrCreate(['name' => 'post.create']);
        Permission::firstOrCreate(['name' => 'post.update']);
        Permission::firstOrCreate(['name' => 'post.destroy']);

        Permission::firstOrCreate(['name' => 'tag.show']);
        Permission::firstOrCreate(['name' => 'tag.create']);
        Permission::firstOrCreate(['name' => 'tag.update']);
        Permission::firstOrCreate(['name' => 'tag.destroy']);

        Permission::firstOrCreate(['name' => 'ban_reason.show']);
        Permission::firstOrCreate(['name' => 'ban_reason.create']);
        Permission::firstOrCreate(['name' => 'ban_reason.update']);
        Permission::firstOrCreate(['name' => 'ban_reason.destroy']);

        Permission::firstOrCreate(['name' => 'category.show']);
        Permission::firstOrCreate(['name' => 'category.create']);
        Permission::firstOrCreate(['name' => 'category.update']);
        Permission::firstOrCreate(['name' => 'category.destroy']);

        Permission::firstOrCreate(['name' => 'gamejolt_account_ban.show']);
        Permission::firstOrCreate(['name' => 'gamejolt_account_ban.create']);
        Permission::firstOrCreate(['name' => 'gamejolt_account_ban.destroy']);

        Permission::firstOrCreate(['name' => 'gamejolt_account.show']);

        Permission::firstOrCreate(['name' => 'discord_bot_setting.show']);
        Permission::firstOrCreate(['name' => 'discord_bot_setting.update']);

        Permission::firstOrCreate(['name' => 'user.show']);

        Permission::firstOrCreate(['name' => 'stats']);
        Permission::firstOrCreate(['name' => 'analytics']);

        // Get all permissions
        $all_permissions = Permission::all();

        // Super Admin permissions
        $super_admin->givePermissionTo($all_permissions);

        // Admin permissions
        // Give all permissions except for permissions containing 'manage'
        $admin->givePermissionTo($all_permissions->filter(function ($permission) {
            return ! Str::contains($permission->name, ['manage']);
        }));

        // Moderator permissions
        // Give all permissions except for permissions containing 'destroy' and 'manage'
        $moderator->givePermissionTo($all_permissions->filter(function ($permission) {
            return ! Str::contains($permission->name, ['destroy', 'manage']);
        }));

        $first_user = User::first();
        if ($first_user) {
            $first_user->assignRole($super_admin);
        }
    }
}
