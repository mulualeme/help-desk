<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class SlaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user ID for the created_by field
        $adminId = User::where('email', 'admin@example.com')->first()?->id ?? 1;
        
        $now = Carbon::now();
        
        // Define SLAs for different priority levels
        $slas = [
            [
                'name' => 'Urgent Priority SLA',
                'description' => 'Service level agreement for urgent priority issues that require immediate attention',
                'response_time_minutes' => 30, // 30 minutes response time
                'resolution_time_minutes' => 240, // 4 hours resolution time
                'customer_id' => null, // This SLA applies to all customers
                'priority' => 'urgent',
                'is_active' => true,
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'High Priority SLA',
                'description' => 'Service level agreement for high priority issues with significant impact',
                'response_time_minutes' => 60, // 1 hour response time
                'resolution_time_minutes' => 480, // 8 hours resolution time
                'customer_id' => null,
                'priority' => 'high',
                'is_active' => true,
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Medium Priority SLA',
                'description' => 'Service level agreement for medium priority issues with moderate impact',
                'response_time_minutes' => 120, // 2 hours response time
                'resolution_time_minutes' => 1440, // 24 hours resolution time
                'customer_id' => null,
                'priority' => 'medium',
                'is_active' => true,
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Low Priority SLA',
                'description' => 'Service level agreement for low priority issues with minimal impact',
                'response_time_minutes' => 240, // 4 hours response time
                'resolution_time_minutes' => 2880, // 48 hours resolution time
                'customer_id' => null,
                'priority' => 'low',
                'is_active' => true,
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Premium Customer SLA',
                'description' => 'Enhanced service level agreement for premium customers',
                'response_time_minutes' => 30, // 30 minutes response time
                'resolution_time_minutes' => 240, // 4 hours resolution time
                'customer_id' => null, // Update this with specific customer IDs as needed
                'priority' => 'medium', // Default priority, will be overridden by ticket priority
                'is_active' => true,
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert SLAs into the database
        DB::table('slas')->insert($slas);
    }
}
