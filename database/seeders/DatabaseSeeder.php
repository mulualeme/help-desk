<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the roles and permissions seeder first
        $this->call(RolesandPermissionSeeder::class);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Agent Users
        $agent1 = User::create([
            'name' => 'Agent One',
            'email' => 'agent1@example.com',
            'password' => Hash::make('password'),
        ]);
        $agent1->assignRole('agent');

        $agent2 = User::create([
            'name' => 'Agent Two',
            'email' => 'agent2@example.com',
            'password' => Hash::make('password'),
        ]);
        $agent2->assignRole('agent');

        // Create additional random agents
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('agent');
        });

        // Create Customer Users
        $customer1 = User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer1->assignRole('customer');

        $customer2 = User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer2->assignRole('customer');

        // Create additional random customers
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('customer');
        });

        // Seed departments and assign agents
        $this->call(DepartmentSeeder::class);
        
        // Seed knowledge base categories and articles
        $this->call(KnowledgeBaseSeeder::class);
    }
}
