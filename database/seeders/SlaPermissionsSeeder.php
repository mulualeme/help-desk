<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SlaPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SLA permissions if they don't exist
        $permissions = [
            'view sla',
            'create sla',
            'edit sla',
            'delete sla',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Find roles
        $adminRole = Role::where('name', 'admin')->first();
        $agentRole = Role::where('name', 'agent')->first();

        // Assign permissions to admin role
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'view sla',
                'create sla',
                'edit sla',
                'delete sla',
            ]);
        }

        // Assign view permission to agent role
        if ($agentRole) {
            $agentRole->givePermissionTo('view sla');
        }
    }
}
