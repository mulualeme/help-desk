<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create departments
        $departments = [
            [
                'name' => 'Customer Support',
                'description' => 'Handles general customer inquiries and issues',
                'is_active' => true,
            ],
            [
                'name' => 'Technical Support',
                'description' => 'Provides technical assistance and troubleshooting',
                'is_active' => true,
            ],
            [
                'name' => 'Billing',
                'description' => 'Handles payment and subscription related inquiries',
                'is_active' => true,
            ],
            [
                'name' => 'Returns & Refunds',
                'description' => 'Processes product returns and refund requests',
                'is_active' => true,
            ],
            [
                'name' => 'Product Information',
                'description' => 'Provides details about products and services',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $departmentData) {
            $department = Department::create($departmentData);
            
            // Get random agents to assign to this department (between 2-5 agents)
            $agents = User::role('agent')
                ->inRandomOrder()
                ->limit(rand(2, 5))
                ->get();
            
            // Attach the agents to the department
            $department->agents()->attach($agents->pluck('id'));
        }
    }
} 