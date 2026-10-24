<?php

namespace Tests\Feature\Owner;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $ownerRole = Role::where('name', 'Owner')->firstOrFail();

        $this->owner = User::factory()->create([
            'role_id' => $ownerRole->id,
            'is_active' => true
        ]);
    }

    public function test_owner_can_open_work_order_page(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.work-orders.index'))
            ->assertOk()
            ->assertViewIs('owner.work-orders.index')
            ->assertViewHasAll([
                'statistics',
                'workOrders',
            ]);
    }

    public function test_work_order_statistics_have_expected_structure(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.work-orders.index'))
            ->assertOk()
            ->assertViewHas('statistics', function (array $statistics) {
                return array_key_exists('total', $statistics)
                    && array_key_exists('active', $statistics)
                    && array_key_exists('completed', $statistics);
            });
    }

    public function test_work_orders_use_ten_items_per_page(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.work-orders.index'))
            ->assertOk()
            ->assertViewHas('workOrders', function ($workOrders) {
                return $workOrders->perPage() === 10;
            });
    }

    public function test_guest_cannot_access_work_order_page(): void
    {
        $this->get(route('owner.work-orders.index'))
            ->assertRedirect(route('login'));
    }
}
