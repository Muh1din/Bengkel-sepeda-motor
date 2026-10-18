<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user dengan role Admin.
     */
    private function createAdmin(): User
    {
        $role = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    /**
     * Membuat review beserta customer, vehicle, dan booking.
     */
    private function createReview(
        string $customerName = 'Test Customer',
        int $rating = 5,
        string $status = 'pending',
        ?string $bookingCode = null
    ): Review {
        $uniqueId = uniqid();

        $customer = Customer::create([
            'user_id' => null,
            'name' => $customerName,
            'phone' => '08' . substr(
                str_pad((string) random_int(100000000, 999999999), 9, '0'),
                0,
                9
            ),
            'email' => null,
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'license_plate' => 'B ' . random_int(1000, 9999) . ' XYZ',
            'brand' => 'Honda',
            'model' => 'CBR 250RR',
            'year' => 2024,
            'color' => 'Black',
        ]);

        $booking = Booking::create([
            'booking_code' => $bookingCode ?? 'BK-' . $uniqueId,
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Repaint',
            'booking_date' => now()->toDateString(),
            'booking_time' => '09:00:00',
            'complaint' => 'Testing review controller',
            'status' => 'COMPLETED',
        ]);

        return Review::create([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'rating' => $rating,
            'comment' => 'Review untuk kebutuhan testing.',
            'status' => $status,
        ]);
    }

    /**
     * Admin dapat membuka halaman review.
     */
    public function test_admin_can_access_review_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertOk();

        $response->assertViewIs('admin.reviews.index');
    }

    /**
     * Dashboard menampilkan statistik review dengan benar.
     */
    public function test_dashboard_displays_correct_review_statistics(): void
    {
        $admin = $this->createAdmin();

        $this->createReview('Customer One', 5, 'published');
        $this->createReview('Customer Two', 4, 'published');
        $this->createReview('Customer Three', 3, 'pending');

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertOk();

        $response->assertViewHas('totalReviews', 3);
        $response->assertViewHas('pendingReviews', 1);
        $response->assertViewHas('publishedReviews', 2);

        $response->assertViewHas('averageRating', function ($averageRating) {
            return abs((float) $averageRating - 4.0) < 0.001;
        });
    }

    /**
     * Review ditampilkan bersama relasi customer dan booking.
     */
    public function test_reviews_include_customer_and_booking_relations(): void
    {
        $admin = $this->createAdmin();

        $review = $this->createReview(
            'Customer Relationship Test',
            5,
            'published'
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) use ($review) {
            $result = $reviews->firstWhere('id', $review->id);

            return $result !== null
                && $result->relationLoaded('customer')
                && $result->relationLoaded('booking')
                && $result->customer !== null
                && $result->booking !== null;
        });
    }

    /**
     * Pencarian berdasarkan nama customer.
     */
    public function test_reviews_can_be_searched_by_customer_name(): void
    {
        $admin = $this->createAdmin();

        $matchingReview = $this->createReview(
            'Customer Search Target',
            5,
            'published'
        );

        $this->createReview(
            'Different Customer',
            3,
            'pending'
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index', [
                'search' => 'Customer Search Target',
            ]));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) use ($matchingReview) {
            return $reviews->total() === 1
                && $reviews->contains('id', $matchingReview->id);
        });
    }

    /**
     * Pencarian berdasarkan kode booking.
     */
    public function test_reviews_can_be_searched_by_booking_code(): void
    {
        $admin = $this->createAdmin();

        $matchingReview = $this->createReview(
            'Booking Search Customer',
            5,
            'published',
            'BK-SEARCH-123'
        );

        $this->createReview(
            'Another Booking Customer',
            3,
            'pending',
            'BK-OTHER-456'
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index', [
                'search' => 'BK-SEARCH-123',
            ]));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) use ($matchingReview) {
            return $reviews->total() === 1
                && $reviews->contains('id', $matchingReview->id);
        });
    }

    /**
     * Review dapat difilter berdasarkan rating.
     */
    public function test_reviews_can_be_filtered_by_rating(): void
    {
        $admin = $this->createAdmin();

        $matchingReview = $this->createReview(
            'Five Star Customer',
            5,
            'published'
        );

        $this->createReview(
            'Three Star Customer',
            3,
            'pending'
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index', [
                'rating' => 5,
            ]));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) use ($matchingReview) {
            return $reviews->total() === 1
                && $reviews->contains('id', $matchingReview->id);
        });
    }

    /**
     * Review dapat difilter berdasarkan status.
     */
    public function test_reviews_can_be_filtered_by_status(): void
    {
        $admin = $this->createAdmin();

        $matchingReview = $this->createReview(
            'Published Customer',
            5,
            'published'
        );

        $this->createReview(
            'Pending Customer',
            4,
            'pending'
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index', [
                'status' => 'published',
            ]));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) use ($matchingReview) {
            return $reviews->total() === 1
                && $reviews->contains('id', $matchingReview->id);
        });
    }

    /**
     * Pagination membatasi jumlah review menjadi 10 per halaman.
     */
    public function test_reviews_are_paginated_ten_per_page(): void
    {
        $admin = $this->createAdmin();

        for ($i = 1; $i <= 12; $i++) {
            $this->createReview(
                'Pagination Customer ' . $i,
                5,
                'published'
            );
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index'));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) {
            return $reviews->total() === 12
                && $reviews->count() === 10
                && $reviews->lastPage() === 2;
        });
    }

    /**
     * Parameter pencarian tetap terbawa dalam pagination.
     */
    public function test_pagination_preserves_query_parameters(): void
    {
        $admin = $this->createAdmin();

        for ($i = 1; $i <= 12; $i++) {
            $this->createReview(
                'Query Customer ' . $i,
                5,
                'published'
            );
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.reviews.index', [
                'search' => 'Query Customer',
                'rating' => 5,
            ]));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) {
            return $reviews->count() === 10
                && $reviews->total() === 12;
        });

        $response->assertSee(
            'search=Query%20Customer',
            false
        );

        $response->assertSee(
            'rating=5',
            false
        );
    }

    /**
     * Admin dapat memublikasikan review.
     */
    public function test_admin_can_publish_review(): void
    {
        $admin = $this->createAdmin();

        $review = $this->createReview(
            'Publish Customer',
            5,
            'pending'
        );

        $response = $this->actingAs($admin)
            ->patch(route('admin.reviews.publish', $review));

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Ulasan berhasil dipublikasikan.'
        );

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'published',
        ]);
    }

    /**
     * Admin dapat menyembunyikan review.
     */
    public function test_admin_can_hide_review(): void
    {
        $admin = $this->createAdmin();

        $review = $this->createReview(
            'Hide Customer',
            4,
            'published'
        );

        $response = $this->actingAs($admin)
            ->patch(route('admin.reviews.hide', $review));

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Ulasan berhasil disembunyikan.'
        );

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'hidden',
        ]);
    }
}
