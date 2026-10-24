<?php

namespace Tests\Feature\Home;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeBookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Data valid untuk membuat booking sebagai guest.
     */
    private function validBookingData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Hitam',
            'service_type' => 'Repaint',
            'booking_date' => today()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Cat bodi motor mulai kusam.',
        ], $overrides);
    }

    /**
     * Halaman form booking dapat dibuka.
     */
    public function test_guest_can_open_booking_form(): void
    {
        $response = $this->get(route('booking.form.index'));

        $response->assertOk();

        $response->assertViewIs('components.home.booking.index');
    }

    /**
     * Booking valid berhasil disimpan.
     */
    public function test_guest_can_create_booking_successfully(): void
    {
        $response = $this->post(
            route('booking.form.store'),
            $this->validBookingData()
        );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHas('success', 'Booking berhasil dibuat!');

        $response->assertSessionHas('booking_code');

        $this->assertDatabaseHas('customers', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'user_id' => null,
        ]);

        $this->assertDatabaseHas('vehicles', [
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
        ]);

        $this->assertDatabaseHas('bookings', [
            'service_type' => 'Repaint',
            'booking_date' => today()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseCount('vehicles', 4);
        $this->assertDatabaseCount('bookings', 1);
    }

    /**
     * Input tertentu dinormalisasi sebelum disimpan.
     */
    public function test_guest_booking_normalizes_input(): void
    {
        $response = $this->post(
            route('booking.form.store'),
            $this->validBookingData([
                'name' => '  Budi Santoso  ',
                'phone' => '  081234567890  ',
                'license_plate' => ' b 1234 xyz ',
            ])
        );

        $response->assertRedirect(route('booking.form.index'));

        $this->assertDatabaseHas('customers', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
        ]);

        $this->assertDatabaseHas('vehicles', [
            'license_plate' => 'B 1234 XYZ',
        ]);
    }

    /**
     * Booking kedua menggunakan customer guest yang sama
     * ketika nomor teleponnya cocok.
     */
    public function test_guest_can_book_again_using_existing_guest_customer(): void
    {
        $this->post(
            route('booking.form.store'),
            $this->validBookingData()
        );

        $this->post(
            route('booking.form.store'),
            $this->validBookingData([
                'license_plate' => 'B 5678 XYZ',
                'service_type' => 'Restorasi Full',
            ])
        );

        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseCount('vehicles', 5);
        $this->assertDatabaseCount('bookings', 2);
    }

    /**
     * Nomor telepon yang terhubung ke akun pelanggan
     * tidak boleh digunakan untuk guest booking.
     */
    public function test_guest_cannot_book_using_registered_customer_phone(): void
    {
        // Menggunakan user yang sudah tersedia dari seeder.
        $user = User::query()->first();

        $this->assertNotNull(
            $user,
            'Test membutuhkan minimal satu user dari seeder.'
        );

        Customer::query()->create([
            'user_id' => $user->id,
            'name' => 'Pelanggan Terdaftar',
            'phone' => '081234567891',
            'email' => null,
            'address' => null,
        ]);

        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData([
                    'phone' => '081234567891',
                ])
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Nomor polisi yang sudah terdaftar pada customer lain
     * tidak boleh digunakan oleh guest yang berbeda.
     */
    public function test_guest_cannot_use_vehicle_owned_by_another_customer(): void
    {
        $user = User::query()->first();

        $this->assertNotNull(
            $user,
            'Test membutuhkan minimal satu user dari seeder.'
        );

        $owner = Customer::query()->create([
            'user_id' => $user->id,
            'name' => 'Pemilik Kendaraan',
            'phone' => '081234567892',
            'email' => null,
            'address' => null,
        ]);

        $owner->vehicles()->create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2023,
            'color' => 'Biru',
        ]);

        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData()
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors('license_plate');

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Service type "Lainnya" membutuhkan custom_service.
     */
    public function test_custom_service_is_required_when_service_type_is_other(): void
    {
        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData([
                    'service_type' => 'Lainnya',
                    'custom_service' => '',
                ])
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors('custom_service');

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Service type di luar pilihan yang diizinkan ditolak.
     */
    public function test_invalid_service_type_is_rejected(): void
    {
        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData([
                    'service_type' => 'Ganti Mesin',
                ])
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors('service_type');

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Tanggal booking sebelum hari ini ditolak.
     */
    public function test_booking_date_cannot_be_in_the_past(): void
    {
        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData([
                    'booking_date' => today()->subDay()->toDateString(),
                ])
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors('booking_date');

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Tahun kendaraan harus berada dalam rentang yang diizinkan.
     */
    public function test_invalid_vehicle_year_is_rejected(): void
    {
        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData([
                    'year' => now()->year + 1,
                ])
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors('year');

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Field wajib yang kosong harus menghasilkan error validasi.
     */
    public function test_required_fields_cannot_be_empty(): void
    {
        $response = $this->from(route('booking.form.index'))
            ->post(
                route('booking.form.store'),
                $this->validBookingData([
                    'name' => '',
                    'phone' => '',
                    'license_plate' => '',
                    'brand' => '',
                    'model' => '',
                    'year' => '',
                    'color' => '',
                    'service_type' => '',
                    'booking_date' => '',
                    'booking_time' => '',
                ])
            );

        $response->assertRedirect(route('booking.form.index'));

        $response->assertSessionHasErrors([
            'name',
            'phone',
            'license_plate',
            'brand',
            'model',
            'year',
            'color',
            'service_type',
            'booking_date',
            'booking_time',
        ]);

        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Service "Lainnya" menyimpan nama layanan custom.
     */
    public function test_custom_service_name_is_saved_as_service_type(): void
    {
        $response = $this->post(
            route('booking.form.store'),
            $this->validBookingData([
                'service_type' => 'Lainnya',
                'custom_service' => 'Pemasangan Aksesori',
            ])
        );

        $response->assertRedirect(route('booking.form.index'));

        $this->assertDatabaseHas('bookings', [
            'service_type' => 'Pemasangan Aksesori',
            'status' => 'PENDING',
        ]);
    }
}
