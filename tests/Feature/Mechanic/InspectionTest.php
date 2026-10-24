<?php

namespace Tests\Feature\Mechanic;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleInspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InspectionTest extends TestCase
{
    use RefreshDatabase;

    private User $mechanic;

    private User $otherMechanic;

    private Customer $customer;

    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        $roleId = DB::table('roles')->insertGetId([
            'name' => 'Mechanic',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->mechanic = $this->createMechanic(
            $roleId,
            'mechanic@test.com',
            '081234567801'
        );

        $this->otherMechanic = $this->createMechanic(
            $roleId,
            'other-mechanic@test.com',
            '081234567802'
        );

        $this->customer = Customer::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'phone' => '081234567803',
            'address' => 'Depok',
            'user_id' => null,
        ]);

        $this->vehicle = Vehicle::create([
            'license_plate' => 'B 1234 TST',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $this->customer->id,
        ]);
    }

    private function createMechanic(
        int $roleId,
        string $email,
        string $phone
    ): User {
        $mechanic = User::create([
            'name' => 'Mechanic Test',
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'role_id' => $roleId,
        ]);

        $mechanic->forceFill([
            'is_active' => true,
        ])->save();

        return $mechanic;
    }

    private function createBooking(): Booking
    {
        return Booking::create([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $this->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'service_type' => 'Servis',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00:00',
            'complaint' => 'Pengujian inspeksi',
            'status' => 'IN_PROGRESS',
        ]);
    }

    private function createInspection(
        ?User $mechanic = null,
        string $status = 'IN_PROGRESS'
    ): VehicleInspection {
        $mechanic ??= $this->mechanic;

        return VehicleInspection::create([
            'booking_id' => $this->createBooking()->id,
            'mechanic_id' => $mechanic->id,
            'status' => $status,
            'inspection' => 'Pemeriksaan awal kendaraan',
            'diagnosis' => 'Pemeriksaan sedang berlangsung',
            'recommendation' => null,
            'created_by' => $mechanic->id,
        ]);
    }
    
    // Index

    public function test_mechanic_can_open_inspection_list(): void
    {
        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.inspections.index'));

        $response->assertOk();

        $response->assertViewIs('mechanic.inspections.index');
    }

    public function test_inspection_list_only_contains_own_in_progress_inspections(): void
    {
        $ownInspection = $this->createInspection(
            $this->mechanic,
            'IN_PROGRESS'
        );

        $this->createInspection(
            $this->mechanic,
            'COMPLETED'
        );

        $this->createInspection(
            $this->otherMechanic,
            'IN_PROGRESS'
        );

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.inspections.index'));

        $response->assertOk();

        $response->assertViewHas('inspections', function ($inspections) use ($ownInspection) {
            return $inspections->count() === 1
                && $inspections->first()->id === $ownInspection->id;
        });
    }

    
    // Show

    public function test_mechanic_can_open_own_inspection_detail(): void
    {
        $inspection = $this->createInspection($this->mechanic);

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.inspections.show', $inspection));

        $response->assertOk();

        $response->assertViewIs('mechanic.inspections.show');

        $response->assertViewHas('inspection', function ($result) use ($inspection) {
            return $result->id === $inspection->id;
        });
    }

    public function test_mechanic_cannot_open_another_mechanics_inspection(): void
    {
        $inspection = $this->createInspection($this->otherMechanic);

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.inspections.show', $inspection));

        $response->assertForbidden();
    }

    // Save Draft

    public function test_mechanic_can_save_inspection_draft(): void
    {
        $inspection = $this->createInspection();

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.save-draft', $inspection), [
                'inspection' => 'Rantai motor perlu diperiksa.',
                'diagnosis' => 'Rantai mulai kendur.',
                'recommendation' => 'Periksa dan setel rantai.',
            ]);

        $response->assertRedirect(
            route('mechanic.inspections.show', $inspection)
        );

        $response->assertSessionHas(
            'success',
            'Draft inspeksi berhasil disimpan.'
        );

        $this->assertDatabaseHas('vehicle_inspections', [
            'id' => $inspection->id,
            'inspection' => 'Rantai motor perlu diperiksa.',
            'diagnosis' => 'Rantai mulai kendur.',
            'recommendation' => 'Periksa dan setel rantai.',
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_mechanic_cannot_save_draft_of_another_mechanics_inspection(): void
    {
        $inspection = $this->createInspection($this->otherMechanic);

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.save-draft', $inspection), [
                'inspection' => 'Perubahan tanpa izin.',
                'diagnosis' => 'Diagnosis tidak sah.',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('vehicle_inspections', [
            'id' => $inspection->id,
            'inspection' => 'Perubahan tanpa izin.',
        ]);
    }

    public function test_completed_inspection_cannot_be_saved_as_draft(): void
    {
        $inspection = $this->createInspection(
            $this->mechanic,
            'COMPLETED'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.save-draft', $inspection), [
                'inspection' => 'Perubahan setelah selesai.',
                'diagnosis' => 'Diagnosis baru.',
            ]);

        $response->assertRedirect(
            route('mechanic.inspections.show', $inspection)
        );

        $response->assertSessionHas(
            'error',
            'Inspeksi ini sudah diselesaikan.'
        );

        $this->assertDatabaseMissing('vehicle_inspections', [
            'id' => $inspection->id,
            'inspection' => 'Perubahan setelah selesai.',
        ]);
    }

    // Complete Inspection

    public function test_mechanic_can_complete_own_inspection(): void
    {
        $inspection = $this->createInspection();

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.complete', $inspection), [
                'inspection' => 'Pemeriksaan mesin dan rem selesai.',
                'diagnosis' => 'Rem perlu disetel.',
                'recommendation' => 'Lakukan penyetelan rem.',
            ]);

        $response->assertRedirect(
            route('mechanic.inspections.index')
        );

        $response->assertSessionHas(
            'success',
            'Inspeksi berhasil diselesaikan dan dikirim ke Service Advisor.'
        );

        $this->assertDatabaseHas('vehicle_inspections', [
            'id' => $inspection->id,
            'inspection' => 'Pemeriksaan mesin dan rem selesai.',
            'diagnosis' => 'Rem perlu disetel.',
            'recommendation' => 'Lakukan penyetelan rem.',
            'status' => 'COMPLETED',
        ]);
    }

    public function test_inspection_cannot_be_completed_without_required_fields(): void
    {
        $inspection = $this->createInspection();

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.complete', $inspection), [
                'inspection' => '',
                'diagnosis' => '',
                'recommendation' => 'Lakukan servis.',
            ]);

        $response->assertSessionHasErrors([
            'inspection',
            'diagnosis',
        ]);

        $this->assertDatabaseHas('vehicle_inspections', [
            'id' => $inspection->id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_mechanic_cannot_complete_another_mechanics_inspection(): void
    {
        $inspection = $this->createInspection($this->otherMechanic);

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.complete', $inspection), [
                'inspection' => 'Pemeriksaan selesai.',
                'diagnosis' => 'Kendaraan normal.',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('vehicle_inspections', [
            'id' => $inspection->id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_completed_inspection_cannot_be_completed_again(): void
    {
        $inspection = $this->createInspection(
            $this->mechanic,
            'COMPLETED'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.inspections.complete', $inspection), [
                'inspection' => 'Pemeriksaan ulang.',
                'diagnosis' => 'Diagnosis ulang.',
            ]);

        $response->assertRedirect(
            route('mechanic.inspections.show', $inspection)
        );

        $response->assertSessionHas(
            'error',
            'Inspeksi ini sudah diselesaikan.'
        );

        $this->assertDatabaseMissing('vehicle_inspections', [
            'id' => $inspection->id,
            'inspection' => 'Pemeriksaan ulang.',
        ]);
    }

    // Authentication

    public function test_guest_cannot_access_inspection_list(): void
    {
        $response = $this->get(route('mechanic.inspections.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_inspection_detail(): void
    {
        $inspection = $this->createInspection();

        $response = $this->get(
            route('mechanic.inspections.show', $inspection)
        );

        $response->assertRedirect(route('login'));
    }
}
