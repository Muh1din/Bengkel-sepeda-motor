<?php

namespace Tests\Feature\Owner;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ItemUsageReportTest extends TestCase
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

    public function test_owner_can_open_item_usage_report(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.item-usage'))
            ->assertOk()
            ->assertViewIs('owner.reports.item-usage')
            ->assertViewHasAll([
                'statistics',
                'itemUsage',
                'startDate',
                'endDate',
            ]);
    }

    public function test_report_uses_current_month_as_default_date_range(): void
    {
        Carbon::setTestNow('2026-10-19 10:00:00');

        try {
            $this->actingAs($this->owner)
                ->get(route('owner.reports.item-usage'))
                ->assertOk()
                ->assertViewHas('startDate', '2026-10-01')
                ->assertViewHas('endDate', '2026-10-19');
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_report_rejects_end_date_before_start_date(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.item-usage', [
                'start_date' => '2026-10-20',
                'end_date' => '2026-10-10',
            ]))
            ->assertSessionHasErrors('end_date');
    }

    public function test_report_rejects_invalid_date_format(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.reports.item-usage', [
                'start_date' => 'bukan-tanggal',
                'end_date' => '2026-10-19',
            ]))
            ->assertSessionHasErrors('start_date');
    }

    public function test_guest_cannot_access_item_usage_report(): void
    {
        $this->get(route('owner.reports.item-usage'))
            ->assertRedirect(route('login'));
    }
}
