<?php

namespace Tests\Feature\Home;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Kode booking valid dan ditemukan.
     */
    public function test_search_redirects_with_booking_id_when_booking_exists(): void
    {
        $booking = Booking::factory()->create([
            'booking_code' => 'BK-ABC123',
        ]);

        $response = $this->post(route('guest.tracking.search'), [
            'booking_code' => 'BK-ABC123',
        ]);

        $response->assertRedirect(url('/#tracking'));

        $response->assertSessionHas(
            'tracking_booking_id',
            $booking->id
        );

        $response->assertSessionHas('tracking_section', true);
        $response->assertSessionHasNoErrors();
    }

    /**
     * Kode booking dengan huruf kecil dinormalisasi menjadi huruf kapital.
     */
    public function test_search_accepts_lowercase_booking_code(): void
    {
        $booking = Booking::factory()->create([
            'booking_code' => 'BK-ABC123',
        ]);

        $response = $this->post(route('guest.tracking.search'), [
            'booking_code' => 'bk-abc123',
        ]);

        $response->assertRedirect(url('/#tracking'));

        $response->assertSessionHas(
            'tracking_booking_id',
            $booking->id
        );

        $response->assertSessionHas('tracking_section', true);
        $response->assertSessionHasNoErrors();
    }

    /**
     * Kode booking yang tidak ditemukan menghasilkan error.
     */
    public function test_search_returns_error_when_booking_does_not_exist(): void
    {
        $response = $this->post(route('guest.tracking.search'), [
            'booking_code' => 'BK-ZZZ999',
        ]);

        $response->assertRedirect(url('/#tracking'));

        $response->assertSessionHasErrors([
            'booking_code' =>
            'Booking tidak ditemukan. Periksa kembali kode booking Anda.',
        ]);

        $response->assertSessionHas('tracking_section', true);
        $response->assertSessionHasInput('booking_code', 'BK-ZZZ999');
        $response->assertSessionMissing('tracking_booking_id');
    }

    /**
     * Kode booking wajib diisi.
     */
    public function test_search_rejects_empty_booking_code(): void
    {
        $response = $this->post(route('guest.tracking.search'), [
            'booking_code' => '',
        ]);

        $response->assertRedirect(url('/#tracking'));

        $response->assertSessionHasErrors([
            'booking_code' => 'Kode booking wajib diisi.',
        ]);

        $response->assertSessionHas('tracking_section', true);
    }

    /**
     * Kode booking dengan format yang tidak valid ditolak.
     */
    public function test_search_rejects_invalid_booking_code_format(): void
    {
        $invalidCodes = [
            'ABC123',
            'BK-123',
            'BK-ABC1234',
            'BK-ABC 12',
            'BK-ABC!23',
        ];

        foreach ($invalidCodes as $code) {
            $response = $this->post(
                route('guest.tracking.search'),
                ['booking_code' => $code]
            );

            $response->assertRedirect(url('/#tracking'));

            $response->assertSessionHasErrors([
                'booking_code' => 'Format kode booking tidak valid.',
            ]);

            $response->assertSessionHas('tracking_section', true);
        }
    }

    /**
     * Input booking_code harus berupa string.
     */
    public function test_search_rejects_non_string_booking_code(): void
    {
        $response = $this->post(route('guest.tracking.search'), [
            'booking_code' => ['BK-ABC123'],
        ]);

        $response->assertRedirect(url('/#tracking'));

        $response->assertSessionHasErrors('booking_code');
        $response->assertSessionHas('tracking_section', true);
    }

    /**
     * Input sebelumnya dipertahankan ketika validasi gagal.
     */
    public function test_search_preserves_old_input_when_validation_fails(): void
    {
        $response = $this->post(route('guest.tracking.search'), [
            'booking_code' => 'INVALID',
        ]);

        $response->assertRedirect(url('/#tracking'));

        $response->assertSessionHasInput('booking_code', 'INVALID');
        $response->assertSessionHasErrors('booking_code');
    }
}
