<?php

namespace Tests\Feature\ServiceAdvisor;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\RecommendedWork;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleInspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RecommendedWorkControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $serviceAdvisor;

    protected User $mechanic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceAdvisor = User::whereHas(
            'role',
            fn($query) => $query->where('name', 'ServiceAdvisor')
        )->firstOrFail();

        $this->mechanic = User::whereHas(
            'role',
            fn($query) => $query->where('name', 'Mechanic')
        )->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_approve_recommended_work(): void
    {
        $recommendedWork = $this->createRecommendedWork();

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.recommended-works.approve',
                $recommendedWork
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Pekerjaan berhasil dicatat sebagai disetujui pelanggan.'
        );

        $this->assertDatabaseHas('recommended_works', [
            'id' => $recommendedWork->id,
            'approval_status' => 'ACCEPTED',
        ]);
    }

    public function test_approving_recommended_work_updates_approval_status(): void
    {
        $recommendedWork = $this->createRecommendedWork([
            'approval_status' => 'PENDING',
        ]);

        $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.recommended-works.approve',
                $recommendedWork
            ));

        $this->assertSame(
            'ACCEPTED',
            $recommendedWork->fresh()->approval_status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_reject_recommended_work(): void
    {
        $recommendedWork = $this->createRecommendedWork();

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.recommended-works.reject',
                $recommendedWork
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Pekerjaan berhasil dicatat sebagai ditolak pelanggan.'
        );

        $this->assertDatabaseHas('recommended_works', [
            'id' => $recommendedWork->id,
            'approval_status' => 'REJECTED',
        ]);
    }

    public function test_rejecting_recommended_work_updates_approval_status(): void
    {
        $recommendedWork = $this->createRecommendedWork([
            'approval_status' => 'PENDING',
        ]);

        $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.recommended-works.reject',
                $recommendedWork
            ));

        $this->assertSame(
            'REJECTED',
            $recommendedWork->fresh()->approval_status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_approve_recommended_work(): void
    {
        $recommendedWork = $this->createRecommendedWork();

        Auth::logout();

        $response = $this->patch(route(
            'service-advisor.recommended-works.approve',
            $recommendedWork
        ));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('recommended_works', [
            'id' => $recommendedWork->id,
            'approval_status' => 'PENDING',
        ]);
    }

    public function test_guest_cannot_reject_recommended_work(): void
    {
        $recommendedWork = $this->createRecommendedWork();

        Auth::logout();

        $response = $this->patch(route(
            'service-advisor.recommended-works.reject',
            $recommendedWork
        ));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('recommended_works', [
            'id' => $recommendedWork->id,
            'approval_status' => 'PENDING',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST DATA HELPERS
    |--------------------------------------------------------------------------
    */

    private function createRecommendedWork(
        array $attributes = []
    ): RecommendedWork {
        $inspection = $this->createVehicleInspection();

        return RecommendedWork::forceCreate(array_merge([
            'vehicle_inspection_id' => $inspection->id,
            'name' => 'Penggantian kampas rem',
            'description' => 'Kampas rem perlu diganti.',
            'estimated_cost' => 150000,
            'approval_status' => 'PENDING',
        ], $attributes));
    }

    private function createVehicleInspection(): VehicleInspection
    {
        $customer = Customer::query()->firstOrFail();

        $vehicle = Vehicle::where('customer_id', $customer->id)
            ->firstOrFail();

        $booking = Booking::forceCreate([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00:00',
            'service_type' => 'Servis',
            'complaint' => 'Pemeriksaan kondisi kendaraan.',
            'status' => 'CONFIRMED',
        ]);

        return VehicleInspection::forceCreate([
            'booking_id' => $booking->id,
            'mechanic_id' => $this->mechanic->id,
            'status' => 'COMPLETED',
            'created_by' => $this->serviceAdvisor->id,
        ]);
    }
}
