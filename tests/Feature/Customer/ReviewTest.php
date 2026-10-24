<?php

namespace Tests\Feature\Customer;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveCustomerUser(
        string $email = 'customer@test.com',
        string $phone = '089900000001'
    ): User {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => $email,
            'phone' => $phone,
            'password' => bcrypt('password'),
            'role_id' => 5,
        ]);

        $user->forceFill([
            'is_active' => true,
        ])->save();

        return $user;
    }

    private function createCustomer(User $user): Customer
    {
        return Customer::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    }

    private function createVehicle(Customer $customer): Vehicle
    {
        return Vehicle::create([
            'license_plate' => 'B ' . (1000 + $customer->id) . ' TST',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);
    }

    private function createCompletedBooking(Customer $customer): Booking
    {
        $vehicle = $this->createVehicle($customer);

        return Booking::create([
            'booking_code' => 'BK-000001',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis',
            'booking_date' => now()->toDateString(),
            'booking_time' => '09:00:00',
            'complaint' => 'Pengujian review',
            'status' => 'COMPLETED',
        ]);
    }

    public function test_customer_can_submit_review_for_completed_booking(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
                'comment' => 'Pelayanan bagus.',
            ]
        );

        $response->assertRedirect(route('customer.riwayatService'));
        $response->assertSessionHas('success', 'Review berhasil diberikan.');

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Pelayanan bagus.',
        ]);
    }

    public function test_customer_cannot_review_booking_that_is_not_completed(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $booking->update([
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
                'comment' => 'Pengujian review.',
            ]
        );

        $response->assertRedirect(route('customer.riwayatService'));
        $response->assertSessionHas(
            'error',
            'Booking belum dapat diberikan review.'
        );

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_customer_cannot_submit_duplicate_review(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $booking->review()->create([
            'customer_id' => $customer->id,
            'rating' => 4,
            'comment' => 'Review pertama.',
        ]);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
                'comment' => 'Review kedua.',
            ]
        );

        $response->assertRedirect(route('customer.riwayatService'));
        $response->assertSessionHas(
            'error',
            'Booking ini sudah memiliki review.'
        );

        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_customer_cannot_review_another_customers_booking(): void
    {
        $userA = $this->createActiveCustomerUser();

        $customerA = $this->createCustomer($userA);

        $userB = $this->createActiveCustomerUser(
            'other@test.com',
            '089900000002'
        );

        $customerB = $this->createCustomer($userB);
        $booking = $this->createCompletedBooking($customerB);

        $response = $this->actingAs($userA)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
                'comment' => 'Mencoba review booking orang lain.',
            ]
        );

        $response->assertNotFound();

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_customer_cannot_submit_review_without_rating(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'comment' => 'Tanpa rating.',
            ]
        );

        $response->assertSessionHasErrors('rating');

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_customer_cannot_submit_rating_outside_valid_range(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 6,
                'comment' => 'Rating tidak valid.',
            ]
        );

        $response->assertSessionHasErrors('rating');

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_customer_can_submit_review_without_comment(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
            ]
        );

        $response->assertRedirect(route('customer.riwayatService'));
        $response->assertSessionHas('success', 'Review berhasil diberikan.');

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => null,
        ]);
    }

    public function test_customer_cannot_submit_comment_longer_than_1000_characters(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $response = $this->actingAs($user)->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
                'comment' => str_repeat('a', 1001),
            ]
        );

        $response->assertSessionHasErrors('comment');

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_guest_cannot_submit_review(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);
        $booking = $this->createCompletedBooking($customer);

        $response = $this->post(
            route('customer.bookings.review.store', $booking->id),
            [
                'rating' => 5,
                'comment' => 'Review oleh guest.',
            ]
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }
}
