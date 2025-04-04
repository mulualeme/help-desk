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
        
        // Customer management permissions
        Permission::create(['name' => 'view customers']);
        Permission::create(['name' => 'create customers']);
        Permission::create(['name' => 'edit customers']);
        Permission::create(['name' => 'delete customers']);

        // Knowledge Base category permissions
        Permission::create(['name' => 'create kb category']);
        Permission::create(['name' => 'edit kb category']);
        Permission::create(['name' => 'delete kb category']);
        Permission::create(['name' => 'view kb category']);

        // Knowledge Base article permissions
        Permission::create(['name' => 'create kb article']);
        Permission::create(['name' => 'edit kb article']);
        Permission::create(['name' => 'delete kb article']);
        Permission::create(['name' => 'view kb article']);
        Permission::create(['name' => 'publish kb article']);

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
            'delete agents',
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'create kb category',
            'edit kb category',
            'delete kb category',
            'view kb category',
            'create kb article',
            'edit kb article',
            'delete kb article',
            'view kb article',
            'publish kb article'
        ]);

        // Agent permissions
        $agentRole->givePermissionTo([
            'view department',
            'view ticket',
            'edit ticket',
            'close ticket',
            'reopen ticket',
            'comment on ticket',
            'view agents',
            'view kb category',
            'view kb article',
            'create kb article',
            'edit kb article'
        ]);

        // Customer permissions
        $customerRole->givePermissionTo([
            'create ticket',
            'view ticket',
            'comment on ticket',
            'view kb category',
            'view kb article'
        ]);
    }
}
