<?php

namespace Tests\Feature\Owner;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class WorkOrderReportTest extends TestCase
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

    public function test_owner_can_open_work_order_report(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders'))
            ->assertOk()
            ->assertViewIs('owner.reports.work-orders')
            ->assertViewHasAll([
                'statistics',
                'statusData',
                'workOrders',
                'startDate',
                'endDate',
            ]);
    }

    public function test_report_uses_current_month_as_default_date_range(): void
    {
        Carbon::setTestNow('2026-10-19 10:00:00');

        try {
            $this->actingAs($this->owner)
                ->get(route('owner.reports.work-orders'))
                ->assertOk()
                ->assertViewHas('startDate', '2026-10-01')
                ->assertViewHas('endDate', '2026-10-19');
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_report_rejects_invalid_start_date(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders', [
                'start_date' => 'bukan-tanggal',
                'end_date' => '2026-10-19',
            ]))
            ->assertSessionHasErrors('start_date');
    }

    public function test_report_rejects_invalid_end_date(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders', [
                'start_date' => '2026-10-01',
                'end_date' => 'bukan-tanggal',
            ]))
            ->assertSessionHasErrors('end_date');
    }

    public function test_report_rejects_end_date_before_start_date(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders', [
                'start_date' => '2026-10-20',
                'end_date' => '2026-10-10',
            ]))
            ->assertSessionHasErrors('end_date');
    }

    public function test_report_accepts_a_valid_date_range(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders', [
                'start_date' => '2026-10-01',
                'end_date' => '2026-10-19',
            ]))
            ->assertOk()
            ->assertViewHas('startDate', '2026-10-01')
            ->assertViewHas('endDate', '2026-10-19');
    }

    public function test_report_statistics_have_expected_keys(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders'))
            ->assertOk()
            ->assertViewHas('statistics', function (array $statistics) {
                return array_keys($statistics) === [
                    'total',
                    'active',
                    'completed',
                    'not_received',
                ];
            });
    }

    public function test_status_data_is_available(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders'))
            ->assertOk()
            ->assertViewHas('statusData');
    }

    public function test_work_orders_use_ten_items_per_page(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.work-orders'))
            ->assertOk()
            ->assertViewHas('workOrders', function ($workOrders) {
                return $workOrders->perPage() === 10;
            });
    }

    public function test_guest_cannot_access_work_order_report(): void
    {
        $this->get(route('owner.reports.work-orders'))
            ->assertRedirect(route('login'));
    }
}
