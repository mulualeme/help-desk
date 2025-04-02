<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesandPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // Department permissions
        Permission::create(['name' => 'create department']);
        Permission::create(['name' => 'edit department']);
        Permission::create(['name' => 'delete department']);
        Permission::create(['name' => 'view department']);
        Permission::create(['name' => 'assign agent to department']);

        // Ticket permissions
        Permission::create(['name' => 'create ticket']);
        Permission::create(['name' => 'edit ticket']);
        Permission::create(['name' => 'delete ticket']);
        Permission::create(['name' => 'view ticket']);
        Permission::create(['name' => 'assign ticket']);
        Permission::create(['name' => 'close ticket']);
        Permission::create(['name' => 'reopen ticket']);
        Permission::create(['name' => 'comment on ticket']);

        // Agent management permissions
        Permission::create(['name' => 'manage agents']);
        Permission::create(['name' => 'view agents']);
        Permission::create(['name' => 'invite agents']);
        Permission::create(['name' => 'edit agent roles']);
        Permission::create(['name' => 'delete agents']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $agentRole = Role::create(['name' => 'agent']);
        $customerRole = Role::create(['name' => 'customer']);

        // Admin permissions
        $adminRole->givePermissionTo([
            'create department',
            'edit department',
            'delete department',
            'view department',
            'assign agent to department',
            'create ticket',
            'edit ticket',
            'delete ticket',
            'view ticket',
            'assign ticket',
            'close ticket',
            'reopen ticket',
            'comment on ticket',
            'manage agents',
            'view agents',
            'invite agents',
            'edit agent roles',
            'delete agents'
        ]);

        // Agent permissions
        $agentRole->givePermissionTo([
            'view department',
            'view ticket',
            'edit ticket',
            'close ticket',
            'reopen ticket',
            'comment on ticket',
            'view agents'
        ]);

        // Customer permissions
        $customerRole->givePermissionTo([
            'create ticket',
            'view ticket',
            'comment on ticket'
        ]);
    }
}
