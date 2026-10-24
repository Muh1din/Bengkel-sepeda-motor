<?php

namespace Tests\Feature\Mechanic;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RequestItemTest extends TestCase
{
    use RefreshDatabase;

    private User $mechanic;
    private User $otherMechanic;

    private int $workOrderId;
    private int $otherWorkOrderId;

    private int $materialId;
    private int $otherMaterialId;

    protected function setUp(): void
    {
        parent::setUp();

        $mechanicRoleId = $this->createRole('Mechanic');

        $this->mechanic = $this->createUser(
            $mechanicRoleId,
            'mechanic'
        );

        $this->otherMechanic = $this->createUser(
            $mechanicRoleId,
            'other-mechanic'
        );

        $this->workOrderId = $this->createWorkOrder(
            $this->mechanic
        );

        $this->otherWorkOrderId = $this->createWorkOrder(
            $this->otherMechanic
        );

        $categoryId = $this->createMaterialCategory();

        $this->materialId = $this->createMaterial(
            $categoryId,
            'Brake Oil',
            'BRK-001',
            20
        );

        $this->otherMaterialId = $this->createMaterial(
            $categoryId,
            'Engine Oil',
            'ENG-001',
            15
        );
    }


    // Helper Methods
    private function createRole(string $name): int
    {
        return DB::table('roles')->insertGetId([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createUser(
        int $roleId,
        string $identifier
    ): User {
        $user = new User();

        $user->forceFill([
            'name' => ucfirst($identifier),
            'email' => $identifier . '@example.test',
            'phone' => '0812' . str_pad(
                (string) random_int(1, 99999999),
                8,
                '0',
                STR_PAD_LEFT
            ),
            'password' => Hash::make('password123'),
            'role_id' => $roleId,
            'is_active' => true,
        ]);

        $user->save();

        return $user;
    }

    private function createCustomer(): int
    {
        return DB::table('customers')->insertGetId([
            'user_id' => null,
            'name' => 'Test Customer',
            'email' => null,
            'phone' => '08' . random_int(100000000, 999999999),
            'address' => 'Test Address',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createVehicle(int $customerId): int
    {
        return DB::table('vehicles')->insertGetId([
            'customer_id' => $customerId,
            'license_plate' => 'B ' . random_int(1000, 9999) . ' XYZ',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Black',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createBooking(
        int $customerId,
        int $vehicleId
    ): int {
        return DB::table('bookings')->insertGetId([
            'booking_code' => 'BK-' . str_pad(
                (string) random_int(1, 999999),
                6,
                '0',
                STR_PAD_LEFT
            ),
            'customer_id' => $customerId,
            'vehicle_id' => $vehicleId,
            'service_type' => 'Servis',
            'booking_date' => now()->toDateString(),
            'booking_time' => '09:00:00',
            'complaint' => 'Test complaint',
            'status' => 'IN_PROGRESS',
            'cancellation_reason' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createWorkOrder(User $mechanic): int
    {
        $customerId = $this->createCustomer();

        $vehicleId = $this->createVehicle($customerId);

        $bookingId = $this->createBooking(
            $customerId,
            $vehicleId
        );

        return DB::table('work_orders')->insertGetId([
            'booking_id' => $bookingId,
            'mechanic_id' => $mechanic->id,
            'work_order_code' => 'WO-' . str_pad(
                (string) random_int(1, 999999),
                6,
                '0',
                STR_PAD_LEFT
            ),
            'received_at' => now(),
            'status' => 'IN_PROGRESS',
            'notes' => null,
            'completed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createMaterialCategory(): int
    {
        return DB::table('material_categories')->insertGetId([
            'name' => 'Test Category',
            'description' => 'Category for feature testing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createMaterial(
        int $categoryId,
        string $name,
        string $barcode,
        int $stock
    ): int {
        return DB::table('materials')->insertGetId([
            'material_category_id' => $categoryId,
            'name' => $name,
            'barcode' => $barcode,
            'unit' => 'pcs',
            'stock' => $stock,
            'minimum_stock' => 5,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    // Index Tests
    public function test_mechanic_can_open_request_item_page_for_own_work_order(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->get(
            route('mechanic.request-item.index', [
                'work_order_id' => $this->workOrderId,
            ])
        );

        $response->assertOk();

        $response->assertViewIs('mechanic.request-item.index');

        $response->assertViewHas('workOrder');

        $response->assertViewHas('materials', function ($materials) {
            return $materials->contains('id', $this->materialId)
                && $materials->contains('id', $this->otherMaterialId);
        });
    }

    public function test_mechanic_cannot_open_another_mechanics_work_order(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->get(
            route('mechanic.request-item.index', [
                'work_order_id' => $this->otherWorkOrderId,
            ])
        );

        $response->assertForbidden();
    }

    public function test_request_item_page_returns_404_when_work_order_does_not_exist(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->get(
            route('mechanic.request-item.index', [
                'work_order_id' => 999999,
            ])
        );

        $response->assertNotFound();
    }

    // Store Tests
    public function test_mechanic_can_create_material_request_with_items(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 2,
                    ],
                    [
                        'material_id' => $this->otherMaterialId,
                        'quantity' => 3,
                    ],
                ],
                'notes' => 'Materials needed for repair',
            ]
        );

        $response->assertRedirect(
            route('mechanic.work-orders.index', [
                'work_order_id' => $this->workOrderId,
            ])
        );

        $response->assertSessionHas(
            'success',
            'Material request created successfully.'
        );

        $this->assertDatabaseHas('material_requests', [
            'work_order_id' => $this->workOrderId,
            'mechanic_id' => $this->mechanic->id,
            'status' => 'PENDING',
            'notes' => 'Materials needed for repair',
        ]);

        $materialRequestId = DB::table('material_requests')
            ->where('work_order_id', $this->workOrderId)
            ->where('mechanic_id', $this->mechanic->id)
            ->value('id');

        $this->assertNotNull($materialRequestId);

        $this->assertDatabaseHas('material_request_items', [
            'material_request_id' => $materialRequestId,
            'material_id' => $this->materialId,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('material_request_items', [
            'material_request_id' => $materialRequestId,
            'material_id' => $this->otherMaterialId,
            'quantity' => 3,
        ]);

        $this->assertDatabaseCount('material_request_items', 2);
    }

    public function test_material_stock_does_not_decrease_when_request_is_pending(): void
    {
        $this->actingAs($this->mechanic);

        $initialStock = DB::table('materials')
            ->where('id', $this->materialId)
            ->value('stock');

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 5,
                    ],
                ],
                'notes' => 'Test pending request',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'work_order_id' => $this->workOrderId,
            'mechanic_id' => $this->mechanic->id,
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $this->materialId,
            'stock' => $initialStock,
        ]);
    }

    public function test_mechanic_cannot_create_request_for_another_mechanics_work_order(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->otherWorkOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 1,
                    ],
                ],
                'notes' => 'Unauthorized request',
            ]
        );

        $response->assertForbidden();

        $this->assertDatabaseCount('material_requests', 0);

        $this->assertDatabaseCount('material_request_items', 0);
    }


    // Validation Tests
    public function test_work_order_id_is_required(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('work_order_id');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_items_are_required(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [],
            ]
        );

        $response->assertSessionHasErrors('items');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_material_id_must_exist(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => 999999,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.material_id');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_quantity_must_be_an_integer_greater_than_zero(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 0,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.quantity');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_quantity_cannot_be_a_decimal(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 1.5,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.quantity');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_notes_must_be_a_string(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 1,
                    ],
                ],
                'notes' => ['invalid notes'],
            ]
        );

        $response->assertSessionHasErrors('notes');

        $this->assertDatabaseCount('material_requests', 0);
    }

    // Authentication Tests
    public function test_guest_is_redirected_to_login_when_opening_request_item_page(): void
    {
        $response = $this->get(
            route('mechanic.request-item.index', [
                'work_order_id' => $this->workOrderId,
            ])
        );

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_create_material_request(): void
    {
        $response = $this->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $this->workOrderId,
                'items' => [
                    [
                        'material_id' => $this->materialId,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('material_requests', 0);
    }
}
