<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin (no tenant)
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@smartfarm.test',
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Create two tenants with agents and fields
        $this->createTenantWithData(
            tenantName: 'Green Valley Farm',
            agentEmail: 'agent1@smartfarm.test',
            agentName: 'John Agent',
            fieldCount: 5,
        );

        $this->createTenantWithData(
            tenantName: 'Sunrise Agriculture',
            agentEmail: 'agent2@smartfarm.test',
            agentName: 'Jane Agent',
            fieldCount: 3,
        );

        // Create another tenant with multiple agents
        $tenant = Tenant::factory()->create(['name' => 'Big Farm Co.']);

        $agents = User::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Mike Field', 'email' => 'mike@smartfarm.test'],
                ['name' => 'Sarah Crops', 'email' => 'sarah@smartfarm.test'],
                ['name' => 'Tom Harvest', 'email' => 'tom@smartfarm.test'],
            )
            ->create([
                'role' => 'agent',
                'tenant_id' => $tenant->id,
            ]);

        // Create fields for each agent
        foreach ($agents as $agent) {
            $fields = Field::factory()
                ->count(rand(2, 4))
                ->create([
                    'agent_id' => $agent->id,
                    'tenant_id' => $tenant->id,
                ]);

            // Create updates for each field
            foreach ($fields as $field) {
                FieldUpdate::factory()
                    ->count(rand(1, 3))
                    ->create([
                        'field_id' => $field->id,
                        'agent_id' => $agent->id,
                    ]);
            }
        }
    }

    /**
     * Create a tenant with one agent and sample fields.
     */
    private function createTenantWithData(
        string $tenantName,
        string $agentEmail,
        string $agentName,
        int $fieldCount,
    ): void {
        $tenant = Tenant::factory()->create(['name' => $tenantName]);

        $agent = User::factory()->create([
            'name' => $agentName,
            'email' => $agentEmail,
            'role' => 'agent',
            'tenant_id' => $tenant->id,
        ]);

        $fields = Field::factory()
            ->count($fieldCount)
            ->create([
                'agent_id' => $agent->id,
                'tenant_id' => $tenant->id,
            ]);

        // Create updates for each field
        foreach ($fields as $field) {
            FieldUpdate::factory()
                ->count(rand(1, 5))
                ->create([
                    'field_id' => $field->id,
                    'agent_id' => $agent->id,
                ]);
        }
    }
}
