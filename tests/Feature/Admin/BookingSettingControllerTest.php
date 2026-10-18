<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingSettingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user Admin yang aktif.
     */
    private function createAdmin(): User
    {
        $adminRole = Role::factory()->create([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }

    /**
     * Membuat user Mechanic yang aktif.
     */
    private function createMechanic(): User
    {
        $mechanicRole = Role::factory()->create([
            'name' => 'Mechanic',
        ]);

        return User::factory()->create([
            'role_id' => $mechanicRole->id,
            'is_active' => true,
        ]);
    }

    /**
     * Membuat pengaturan kapasitas booking.
     */
    private function createBookingSetting(int $capacity = 10): Setting
    {
        return Setting::updateOrCreate(
            ['key' => 'max_booking_slots'],
            ['value' => (string) $capacity]
        );
    }

   
    /**
     * Admin dapat membuka halaman pengaturan booking.
     */
    public function test_admin_can_access_booking_settings_page(): void
    {
        $admin = $this->createAdmin();

        $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking-settings.edit'));

        $response->assertOk();

        $response->assertViewIs('admin.booking-settings.edit');

        $response->assertViewHasAll([
            'maxBookingSlots',
            'usedBookingSlots',
            'remainingBookingSlots',
            'activeVehicle',
        ]);
    }

    /**
     * Halaman menampilkan kapasitas maksimum dari database.
     */
    public function test_edit_displays_maximum_booking_capacity(): void
    {
        $admin = $this->createAdmin();

        $this->createBookingSetting(25);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking-settings.edit'));

        $response->assertOk();

        $response->assertViewHas('maxBookingSlots', 25);
    }

    /**
     * Halaman menampilkan jumlah booking aktif.
     */
    public function test_edit_displays_used_booking_slots(): void
    {
        $admin = $this->createAdmin();

        $this->createBookingSetting(10);

        Booking::factory()->create([
            'status' => 'CONFIRMED',
        ]);

        Booking::factory()->create([
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking-settings.edit'));

        $response->assertOk();

        $response->assertViewHas('usedBookingSlots', 2);
    }

    /**
     * Halaman menghitung sisa kapasitas booking.
     */
    public function test_edit_displays_remaining_booking_slots(): void
    {
        $admin = $this->createAdmin();

        $this->createBookingSetting(10);

        Booking::factory()->create([
            'status' => 'CONFIRMED',
        ]);

        Booking::factory()->create([
            'status' => 'IN_PROGRESS',
        ]);

        Booking::factory()->create([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking-settings.edit'));

        $response->assertOk();

        $response->assertViewHas('maxBookingSlots', 10);

        $response->assertViewHas('usedBookingSlots', 2);

        $response->assertViewHas('remainingBookingSlots', 8);
    }

    /**
     * Booking yang tidak aktif tidak dihitung sebagai slot terpakai.
     */
    public function test_edit_does_not_count_inactive_booking_statuses(): void
    {
        $admin = $this->createAdmin();

        $this->createBookingSetting(10);

        Booking::factory()->create([
            'status' => 'PENDING',
        ]);

        Booking::factory()->create([
            'status' => 'REJECTED',
        ]);

        Booking::factory()->create([
            'status' => 'CANCELLED',
        ]);

        Booking::factory()->create([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking-settings.edit'));

        $response->assertOk();

        $response->assertViewHas('usedBookingSlots', 0);

        $response->assertViewHas('remainingBookingSlots', 10);
    }

    /**
     * Admin berhasil memperbarui kapasitas booking.
     */
    public function test_admin_can_update_booking_capacity(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 20,
            ]);

        $response->assertRedirect(
            route('admin.booking-settings.edit')
        );

        $response->assertSessionHas(
            'success',
            'Maksimal slot booking berhasil diperbarui.'
        );

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'key' => 'max_booking_slots',
            'value' => '20',
        ]);
    }

    /**
     * Kapasitas dapat diubah menjadi 1.
     */
    public function test_booking_capacity_can_be_set_to_one(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 1,
            ]);

        $response->assertRedirect(
            route('admin.booking-settings.edit')
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '1',
        ]);
    }

    /**
     * Kapasitas dapat diubah sampai batas maksimum 10000.
     */
    public function test_booking_capacity_can_be_set_to_maximum_allowed_value(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 10000,
            ]);

        $response->assertRedirect(
            route('admin.booking-settings.edit')
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '10000',
        ]);
    }

    /**
     * Kapasitas booking wajib diisi.
     */
    public function test_booking_capacity_is_required(): void
    {
        $admin = $this->createAdmin();

        $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), []);

        $response->assertSessionHasErrors([
            'max_booking_slots',
        ]);
    }

    /**
     * Kapasitas booking harus berupa integer.
     */
    public function test_booking_capacity_must_be_integer(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 'abc',
            ]);

        $response->assertSessionHasErrors([
            'max_booking_slots',
        ]);

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '10',
        ]);
    }

    /**
     * Kapasitas booking tidak boleh kurang dari 1.
     */
    public function test_booking_capacity_must_be_at_least_one(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 0,
            ]);

        $response->assertSessionHasErrors([
            'max_booking_slots',
        ]);

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '10',
        ]);
    }

    /**
     * Kapasitas booking tidak boleh melebihi 10000.
     */
    public function test_booking_capacity_cannot_exceed_10000(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 10001,
            ]);

        $response->assertSessionHasErrors([
            'max_booking_slots',
        ]);

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '10',
        ]);
    }

    /**
     * Kapasitas tidak boleh lebih kecil dari booking aktif.
     */
    public function test_booking_capacity_cannot_be_less_than_used_slots(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        Booking::factory()->create([
            'status' => 'CONFIRMED',
        ]);

        Booking::factory()->create([
            'status' => 'CONFIRMED',
        ]);

        Booking::factory()->create([
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 2,
            ]);

        $response->assertSessionHasErrors([
            'max_booking_slots',
        ]);

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '10',
        ]);
    }

    /**
     * Kapasitas boleh sama dengan jumlah booking aktif.
     */
    public function test_booking_capacity_can_equal_used_slots(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        Booking::factory()->create([
            'status' => 'CONFIRMED',
        ]);

        Booking::factory()->create([
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 2,
            ]);

        $response->assertRedirect(
            route('admin.booking-settings.edit')
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '2',
        ]);
    }

    /**
     * Booking dengan status tidak aktif tidak menghalangi
     * penurunan kapasitas.
     */
    public function test_inactive_bookings_do_not_prevent_capacity_reduction(): void
    {
        $admin = $this->createAdmin();

        $setting = $this->createBookingSetting(10);

        Booking::factory()->create([
            'status' => 'COMPLETED',
        ]);

        Booking::factory()->create([
            'status' => 'CANCELLED',
        ]);

        Booking::factory()->create([
            'status' => 'REJECTED',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 1,
            ]);

        $response->assertRedirect(
            route('admin.booking-settings.edit')
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '1',
        ]);
    }

    /**
     * Update gagal jika pengaturan kapasitas belum tersedia.
     */
    public function test_update_fails_when_booking_setting_does_not_exist(): void
    {
        $admin = $this->createAdmin();

        Setting::where('key', 'max_booking_slots')->delete();

        $this->assertDatabaseMissing('settings', [
            'key' => 'max_booking_slots',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.booking-settings.edit'))
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 20,
            ]);

        $response->assertSessionHasErrors([
            'max_booking_slots',
        ]);

        $this->assertDatabaseMissing('settings', [
            'key' => 'max_booking_slots',
        ]);
    }

    /**
     * Guest tidak dapat membuka halaman pengaturan booking.
     */
    public function test_guest_cannot_access_booking_settings_page(): void
    {
        $response = $this->get(
            route('admin.booking-settings.edit')
        );

        $response->assertRedirect(
            route('login')
        );
    }

    /**
     * Guest tidak dapat memperbarui kapasitas booking.
     */
    public function test_guest_cannot_update_booking_capacity(): void
    {
        $this->createBookingSetting(10);

        $response = $this->put(
            route('admin.booking-settings.update'),
            [
                'max_booking_slots' => 20,
            ]
        );

        $response->assertRedirect(
            route('login')
        );

        $this->assertDatabaseHas('settings', [
            'key' => 'max_booking_slots',
            'value' => '10',
        ]);
    }

    /**
     * User aktif non-Admin tidak dapat membuka halaman pengaturan.
     */
    public function test_non_admin_cannot_access_booking_settings_page(): void
    {
        $mechanic = $this->createMechanic();

        $this->createBookingSetting(10);

        $response = $this->actingAs($mechanic)
            ->get(route('admin.booking-settings.edit'));

        $response->assertForbidden();
    }

    /**
     * User aktif non-Admin tidak dapat memperbarui kapasitas.
     */
    public function test_non_admin_cannot_update_booking_capacity(): void
    {
        $mechanic = $this->createMechanic();

        $setting = $this->createBookingSetting(10);

        $response = $this->actingAs($mechanic)
            ->put(route('admin.booking-settings.update'), [
                'max_booking_slots' => 20,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'value' => '10',
        ]);
    }
}
