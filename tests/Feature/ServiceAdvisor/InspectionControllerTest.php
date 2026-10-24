<?php

namespace Tests\Feature\ServiceAdvisor;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleInspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class InspectionControllerTest extends TestCase
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
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_inspection_index(): void
    {
        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.inspections.index'));

        $response->assertOk();

        $response->assertViewIs('service-advisor.inspections.index');

        $response->assertViewHas('inspections');
    }

    public function test_inspection_index_only_displays_completed_inspections(): void
    {
        $completedInspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $this->createInspection([
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.inspections.index'));

        $response->assertOk();

        $response->assertViewHas('inspections', function ($inspections) use (
            $completedInspection
        ) {
            return $inspections->contains(
                'id',
                $completedInspection->id
            ) && $inspections->every(
                fn($inspection) => $inspection->status === 'COMPLETED'
            );
        });
    }

    public function test_inspection_index_loads_required_relationships(): void
    {
        $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.inspections.index'));

        $response->assertOk();

        $response->assertViewHas('inspections', function ($inspections) {
            $inspection = $inspections->first();

            if (!$inspection) {
                return false;
            }

            return $inspection->relationLoaded('booking')
                && $inspection->booking->relationLoaded('customer')
                && $inspection->booking->relationLoaded('vehicle')
                && $inspection->relationLoaded('mechanic')
                && $inspection->relationLoaded('recommendedWorks');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_inspection_detail(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route(
                'service-advisor.inspections.show',
                $inspection
            ));

        $response->assertOk();

        $response->assertViewIs('service-advisor.inspections.show');

        $response->assertViewHas(
            'inspection',
            fn($viewInspection) => $viewInspection->id === $inspection->id
        );
    }

    public function test_inspection_detail_loads_required_relationships(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route(
                'service-advisor.inspections.show',
                $inspection
            ));

        $response->assertOk();

        $response->assertViewHas('inspection', function ($viewInspection) {
            return $viewInspection->relationLoaded('booking')
                && $viewInspection->booking->relationLoaded('customer')
                && $viewInspection->booking->relationLoaded('vehicle')
                && $viewInspection->relationLoaded('mechanic')
                && $viewInspection->relationLoaded('recommendedWorks');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_open_inspection_index(): void
    {
        Auth::logout();

        $response = $this->get(
            route('service-advisor.inspections.index')
        );

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_open_inspection_detail(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        Auth::logout();

        $response = $this->get(
            route('service-advisor.inspections.show', $inspection)
        );

        $response->assertRedirect(route('login'));
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function createInspection(array $attributes = []): VehicleInspection
    {
        $customer = Customer::query()->firstOrFail();

        $vehicle = Vehicle::where('customer_id', $customer->id)
            ->firstOrFail();

        $booking = Booking::create([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00:00',
            'service_type' => 'Servis',
            'complaint' => 'Pemeriksaan kondisi kendaraan.',
            'status' => 'CONFIRMED',
        ]);

        return VehicleInspection::create(array_merge([
            'booking_id' => $booking->id,
            'mechanic_id' => $this->mechanic->id,
            'status' => 'COMPLETED',
            'created_by' => $this->serviceAdvisor->id,
        ], $attributes));
    }
}
