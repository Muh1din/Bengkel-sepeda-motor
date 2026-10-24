<?php

namespace Tests\Feature\Home;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Review;
use App\Services\BookingCapacityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman utama berhasil ditampilkan.
     */
    public function test_home_page_can_be_opened(): void
    {
        $this->mockCapacityService();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewIs('app.index');

        $response->assertViewHasAll([
            'booking',
            'projects',
            'reviews',
            'maxBookingSlots',
            'remainingBookingSlots',
            'activeVehicle',
        ]);
    }

    /**
     * Booking bernilai null jika session tracking tidak tersedia.
     */
    public function test_booking_is_null_without_tracking_session(): void
    {
        $this->mockCapacityService();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('booking', null);
    }

    /**
     * Booking diambil berdasarkan ID pada session tracking.
     */
    public function test_booking_is_loaded_from_tracking_session(): void
    {
        $this->mockCapacityService();

        $booking = Booking::factory()->create();

        $response = $this
            ->withSession([
                'tracking_booking_id' => $booking->id,
            ])
            ->get(route('home'));

        $response->assertOk();

        $response->assertViewHas('booking', function ($result) use ($booking) {
            return $result !== null
                && $result->id === $booking->id;
        });
    }

    /**
     * Booking tidak ditemukan jika ID pada session tidak valid.
     */
    public function test_booking_is_null_when_tracking_id_does_not_exist(): void
    {
        $this->mockCapacityService();

        $response = $this
            ->withSession([
                'tracking_booking_id' => 999999999,
            ])
            ->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('booking', null);
    }

    /**
     * Hanya proyek published yang ditampilkan.
     */
    public function test_only_published_projects_are_displayed(): void
    {
        $this->mockCapacityService();

        $categoryId = $this->createProjectCategory();

        $publishedProjectId = $this->createProject(
            $categoryId,
            'published'
        );

        $this->createProject($categoryId, 'draft');

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertViewHas('projects', function ($projects) use (
            $publishedProjectId
        ) {
            return $projects->contains(
                fn($project) => $project->id === $publishedProjectId
            ) && $projects->every(
                fn($project) => $project->status === 'published'
            );
        });
    }

    /**
     * Jumlah proyek yang ditampilkan maksimal empat.
     */
    public function test_at_most_four_published_projects_are_displayed(): void
    {
        $this->mockCapacityService();

        $categoryId = $this->createProjectCategory();

        for ($i = 1; $i <= 6; $i++) {
            $this->createProject(
                $categoryId,
                'published',
                "Test Project {$i}"
            );
        }

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertViewHas('projects', function ($projects) {
            return $projects->count() === 4;
        });
    }

    /**
     * Hanya review published yang ditampilkan.
     */
    public function test_only_published_reviews_are_displayed(): void
    {
        $this->mockCapacityService();

        $customer = Customer::factory()->create();

        $publishedReviewId = $this->createReview(
            $customer,
            'published'
        );

        $this->createReview($customer, 'draft');

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) use (
            $publishedReviewId
        ) {
            return $reviews->contains(
                fn($review) => $review->id === $publishedReviewId
            ) && $reviews->every(
                fn($review) => $review->status === 'published'
            );
        });
    }

    /**
     * Jumlah review yang ditampilkan maksimal enam.
     */
    public function test_at_most_six_published_reviews_are_displayed(): void
    {
        $this->mockCapacityService();

        $customer = Customer::factory()->create();

        for ($i = 0; $i < 8; $i++) {
            $this->createReview($customer, 'published');
        }

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertViewHas('reviews', function ($reviews) {
            return $reviews->count() === 6;
        });
    }

    /**
     * Membuat mock BookingCapacityService.
     */
    private function mockCapacityService(): void
    {
        $this->mock(BookingCapacityService::class, function ($mock) {
            $mock->shouldReceive('getMaxSlots')
                ->once()
                ->andReturn(10);

            $mock->shouldReceive('getRemainingSlots')
                ->once()
                ->andReturn(8);

            $mock->shouldReceive('getActiveVehicle')
                ->once()
                ->andReturn(null);
        });
    }

    /**
     * Membuat kategori proyek langsung melalui database.
     */
    private function createProjectCategory(): int
    {
        return DB::table('project_categories')->insertGetId([
            'name' => 'Test Category',
            'slug' => 'test-category-' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Membuat proyek dengan kategori yang valid.
     */
    private function createProject(
        int $categoryId,
        string $status,
        ?string $title = null
    ): int {
        $title ??= 'Test Project ' . uniqid();

        return DB::table('projects')->insertGetId([
            'project_category_id' => $categoryId,
            'title' => $title,
            'slug' => 'test-project-' . uniqid(),
            'vehicle_name' => 'Test Motorcycle',
            'summary' => 'Test project summary',
            'description' => 'Test project description',
            'status' => $status,
            'completed_at' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Membuat review beserta booking yang valid.
     */
    private function createReview(
        Customer $customer,
        string $status
    ): int {
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
        ]);

        return DB::table('reviews')->insertGetId([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Test review',
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
