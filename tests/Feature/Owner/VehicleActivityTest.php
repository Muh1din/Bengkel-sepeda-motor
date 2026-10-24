<?php

namespace Tests\Feature\Owner;

use App\Models\Booking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleActivityTest extends TestCase
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

    public function test_owner_can_open_vehicle_activity_page(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.vehicle-activities.index'))
            ->assertOk()
            ->assertViewIs('owner.vehicle-activities.index')
            ->assertViewHas('vehicleActivities');
    }

    public function test_vehicle_activity_page_uses_ten_items_per_page(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('owner.vehicle-activities.index'));

        $response->assertOk()
            ->assertViewHas('vehicleActivities', function ($vehicleActivities) {
                return $vehicleActivities->perPage() === 10;
            });
    }

    public function test_vehicle_activities_are_ordered_by_latest_booking(): void
    {
        // Buat dua booking menggunakan factory yang valid untuk proyek lu.
        $olderBooking = Booking::factory()->create([
            'created_at' => now()->subDays(2),
        ]);

        $newerBooking = Booking::factory()->create([
            'created_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.vehicle-activities.index'));

        $response->assertOk();

        $response->assertViewHas('vehicleActivities', function ($vehicleActivities) use (
            $olderBooking,
            $newerBooking
        ) {
            $bookings = $vehicleActivities->getCollection();

            return $bookings->search(
                fn($booking) => $booking->id === $newerBooking->id
            ) < $bookings->search(
                fn($booking) => $booking->id === $olderBooking->id
            );
        });
    }

    public function test_guest_cannot_access_vehicle_activity_page(): void
    {
        $this->get(route('owner.vehicle-activities.index'))
            ->assertRedirect(route('login'));
    }
}
