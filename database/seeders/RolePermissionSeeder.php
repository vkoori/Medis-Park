<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = ['create', 'read', 'update', 'delete'];
        $allPermissions = [];

        // 1. Iterate through enabled modules
        $modules = collect(Module::all())
            ->filter(fn ($module) => $module->isEnabled())
            ->keys()
            ->map(fn ($name) => strtolower($name))
            ->toArray();

        foreach ($modules as $module) {
            // 2. Create permissions for each action
            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $allPermissions[] = $permission->name;
            }

            // 3. Create module-specific roles
            Role::firstOrCreate(['name' => "{$module}.viewer"])
                ->syncPermissions(["{$module}.read"]);

            Role::firstOrCreate(['name' => "{$module}.editor"])
                ->syncPermissions(["{$module}.read", "{$module}.update"]);

            Role::firstOrCreate(['name' => "{$module}.manager"])
                ->syncPermissions([
                    "{$module}.create",
                    "{$module}.read",
                    "{$module}.update",
                    "{$module}.delete",
                ]);
        }

        // 4. Create super admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->syncPermissions($allPermissions);
    }
}
