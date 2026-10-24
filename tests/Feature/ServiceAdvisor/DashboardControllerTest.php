<?php

namespace Tests\Feature\ServiceAdvisor;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $serviceAdvisor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceAdvisor = User::whereHas('role', function ($query) {
            $query->where('name', 'ServiceAdvisor');
        })->firstOrFail();
    }

    public function test_service_advisor_can_open_dashboard(): void
    {
        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewIs('service-advisor.dashboard');
    }

    public function test_dashboard_receives_required_view_data(): void
    {
        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewHasAll([
            'bookings',
            'pendingBooking',
            'bookingToday',
            'activeServicesList',
            'activeWorkOrders',
            'mechanics',
        ]);
    }

    public function test_dashboard_counts_pending_bookings(): void
    {
        Booking::factory()->create([
            'status' => 'PENDING',
        ]);

        Booking::factory()->create([
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewHas('pendingBooking', function ($count) {
            return $count === 1;
        });
    }

    public function test_dashboard_counts_bookings_scheduled_for_today(): void
    {
        Booking::factory()->create([
            'booking_date' => today()->toDateString(),
        ]);

        Booking::factory()->create([
            'booking_date' => today()->addDay()->toDateString(),
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewHas('bookingToday', function ($count) {
            return $count === 1;
        });
    }

    public function test_dashboard_shows_only_todays_bookings_in_schedule(): void
    {
        Booking::factory()->create([
            'booking_date' => today()->toDateString(),
        ]);

        Booking::factory()->create([
            'booking_date' => today()->addDay()->toDateString(),
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            return $bookings->count() === 1
                && $bookings->every(
                fn($booking) => $booking->booking_date
                    === today()->toDateString()
                );
        });
    }

    public function test_dashboard_counts_active_work_orders(): void
    {
        $this->createWorkOrderWithBooking('IN_PROGRESS');
        $this->createWorkOrderWithBooking('COMPLETED');

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewHas('activeWorkOrders', function ($count) {
            return $count === 1;
        });
    }

    public function test_dashboard_lists_only_bookings_with_in_progress_status_as_active_services(): void
    {
        Booking::factory()->create([
            'status' => 'IN_PROGRESS',
        ]);

        Booking::factory()->create([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.dashboard'));

        $response->assertOk();

        $response->assertViewHas('activeServicesList', function ($bookings) {
            return $bookings->count() === 1
                && $bookings->every(
                    fn($booking) => $booking->status === 'IN_PROGRESS'
                );
        });
    }

    public function test_guest_cannot_open_service_advisor_dashboard(): void
    {
        $response = $this->get(route('service-advisor.dashboard'));

        $response->assertRedirect(route('login'));
    }

    private function createWorkOrderWithBooking(string $status): void
    {
        $customer = \App\Models\Customer::query()->firstOrFail();

        $vehicle = \App\Models\Vehicle::where(
            'customer_id',
            $customer->id
        )->firstOrFail();

        $booking = Booking::forceCreate([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => today()->toDateString(),
            'booking_time' => '10:00:00',
            'service_type' => 'Servis',
            'complaint' => 'Pemeriksaan kondisi kendaraan.',
            'status' => 'IN_PROGRESS',
        ]);

        $mechanic = User::whereHas('role', function ($query) {
            $query->where('name', 'Mechanic');
        })->firstOrFail();

        \App\Models\WorkOrder::forceCreate([
            'booking_id' => $booking->id,
            'mechanic_id' => $mechanic->id,
            'work_order_code' => 'WO-' . fake()->unique()->numerify('######'),
            'received_at' => now(),
            'status' => $status,
        ]);
    }
}
