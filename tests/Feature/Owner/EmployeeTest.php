<?php

namespace Tests\Feature\Owner;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private int $ownerRoleId;
    private int $advisorRoleId;
    private int $mechanicRoleId;
    private int $adminRoleId;
    private int $customerRoleId;

    private User $owner;

    private int $sequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ownerRoleId = $this->createRole('Owner');
        $this->advisorRoleId = $this->createRole('ServiceAdvisor');
        $this->mechanicRoleId = $this->createRole('Mechanic');
        $this->adminRoleId = $this->createRole('Admin');
        $this->customerRoleId = $this->createRole('Customer');

        $this->owner = $this->createUser(
            $this->ownerRoleId,
            'owner'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

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
        string $identifier,
        array $overrides = []
    ): User {
        $this->sequence++;

        $user = new User();

        $user->forceFill(array_merge([
            'name' => ucfirst($identifier),
            'email' => $identifier . $this->sequence . '@example.test',
            'phone' => '08' . str_pad(
                (string) $this->sequence,
                10,
                '0',
                STR_PAD_LEFT
            ),
            'password' => Hash::make('password123'),
            'role_id' => $roleId,
            'is_active' => true,
        ], $overrides));

        $user->save();

        return $user;
    }

    private function employeePayload(
        int $roleId,
        array $overrides = []
    ): array {
        return array_merge([
            'name' => 'Pegawai Baru',
            'email' => 'pegawai.baru@example.test',
            'phone' => '0812348888',
            'role_id' => $roleId,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    private function createEmployee(
        int $roleId,
        string $prefix = 'employee',
        array $overrides = []
    ): User {
        return $this->createUser(
            $roleId,
            $prefix,
            $overrides
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function test_owner_can_open_employee_index(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.employees.index'))
            ->assertOk()
            ->assertViewIs('owner.employees.index')
            ->assertViewHas('employees')
            ->assertViewHas('roles')
            ->assertViewHas('statistics');
    }

    public function test_employee_index_only_displays_employee_roles(): void
    {
        $advisor = $this->createEmployee(
            $this->advisorRoleId,
            'advisor'
        );

        $mechanic = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $admin = $this->createEmployee(
            $this->adminRoleId,
            'admin'
        );

        $customer = $this->createUser(
            $this->customerRoleId,
            'customer'
        );

        $anotherOwner = $this->createUser(
            $this->ownerRoleId,
            'another-owner'
        );

        $response = $this->actingAs($this->owner)
            ->get(route('owner.employees.index'))
            ->assertOk();

        $employees = $response->viewData('employees');

        $employeeIds = $employees->getCollection()
            ->pluck('id')
            ->all();

        $this->assertContains($advisor->id, $employeeIds);
        $this->assertContains($mechanic->id, $employeeIds);
        $this->assertContains($admin->id, $employeeIds);

        $this->assertNotContains($customer->id, $employeeIds);
        $this->assertNotContains($anotherOwner->id, $employeeIds);
        $this->assertNotContains($this->owner->id, $employeeIds);
    }

    public function test_employee_index_displays_correct_statistics(): void
    {
        $this->createEmployee($this->advisorRoleId, 'advisor');
        $this->createEmployee($this->advisorRoleId, 'advisor');
        $this->createEmployee($this->mechanicRoleId, 'mechanic');
        $this->createEmployee($this->adminRoleId, 'admin');

        $this->createUser($this->customerRoleId, 'customer');

        $response = $this->actingAs($this->owner)
            ->get(route('owner.employees.index'))
            ->assertOk();

        $statistics = $response->viewData('statistics');

        // 4 pegawai dari test + 3 pegawai dari seeder.
        $this->assertSame(7, $statistics['total']);

        // 2 Service Advisor dari test + 1 dari seeder.
        $this->assertSame(3, $statistics['service_advisor']);

        // 1 Mechanic dari test + 1 dari seeder.
        $this->assertSame(2, $statistics['mechanic']);

        // 1 Admin dari test + 1 dari seeder.
        $this->assertSame(2, $statistics['admin']);
    }

    public function test_employee_index_paginates_ten_employees_per_page(): void
    {
        User::factory()
            ->count(12)
            ->create([
                'role_id' => $this->mechanicRoleId,
            ]);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.employees.index'))
            ->assertOk();

        $employees = $response->viewData('employees');

        $this->assertSame(10, $employees->perPage());
        $this->assertSame(15, $employees->total());
        $this->assertSame(10, $employees->count());

        $this->actingAs($this->owner)
            ->get(route('owner.employees.index', ['page' => 2]))
            ->assertOk()
            ->assertViewHas('employees', function ($employees) {
                return $employees->currentPage() === 2
                    && $employees->count() === 5;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function test_owner_can_open_create_employee_page(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('owner.employees.create'))
            ->assertOk()
            ->assertViewIs('owner.employees.create')
            ->assertViewHas('roles');

        $roles = $response->viewData('roles');

        $roleNames = $roles->pluck('name')->all();

        $this->assertContains('ServiceAdvisor', $roleNames);
        $this->assertContains('Mechanic', $roleNames);
        $this->assertContains('Admin', $roleNames);

        $this->assertNotContains('Owner', $roleNames);
        $this->assertNotContains('Customer', $roleNames);
    }

    public function test_owner_can_create_service_advisor_account(): void
    {
        $payload = $this->employeePayload($this->advisorRoleId);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.employees.store'), $payload);

        $response->assertRedirect(route('owner.employees.index'))
            ->assertSessionHas(
                'success',
                'Akun pegawai berhasil dibuat.'
            );

        $this->assertDatabaseHas('users', [
            'name' => 'Pegawai Baru',
            'email' => 'pegawai.baru@example.test',
            'phone' => '0812348888',
            'role_id' => $this->advisorRoleId,
        ]);

        $user = User::where(
            'email',
            'pegawai.baru@example.test'
        )->firstOrFail();

        $this->assertTrue(
            Hash::check('password123', $user->password)
        );

        $this->assertNotSame(
            'password123',
            $user->password
        );
    }

    public function test_owner_can_create_mechanic_account(): void
    {
        $payload = $this->employeePayload(
            $this->mechanicRoleId,
            [
                'email' => 'mechanic.new@example.test',
                'phone' => '081234567891',
            ]
        );

        $this->actingAs($this->owner)
            ->post(route('owner.employees.store'), $payload)
            ->assertRedirect(route('owner.employees.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Pegawai Baru',
            'email' => 'mechanic.new@example.test',
            'phone' => '081234567891',
            'role_id' => $this->mechanicRoleId,
        ]);
    }

    public function test_owner_can_create_admin(): void
    {
        $payload = $this->employeePayload(
            $this->adminRoleId,
            [
                'name' => 'Pegawai Baru',
                'email' => 'admin.new@example.test',
                'phone' => '081234567893',
            ]
        );

        $this->actingAs($this->owner)
            ->post(route('owner.employees.store'), $payload)
            ->assertRedirect(route('owner.employees.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin.new@example.test',
            'phone' => '081234567893',
            'role_id' => $this->adminRoleId,
        ]);
    }

    public function test_create_employee_rejects_owner_role(): void
    {
        $payload = $this->employeePayload($this->ownerRoleId);

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertRedirect(route('owner.employees.create'))
            ->assertSessionHasErrors('role_id');

        $this->assertDatabaseMissing('users', [
            'email' => 'pegawai.baru@example.test',
        ]);
    }

    public function test_create_employee_rejects_customer_role(): void
    {
        $payload = $this->employeePayload($this->customerRoleId);

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertRedirect(route('owner.employees.create'))
            ->assertSessionHasErrors('role_id');
    }

    public function test_create_employee_requires_name(): void
    {
        $payload = $this->employeePayload(
            $this->advisorRoleId,
            ['name' => '']
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertSessionHasErrors('name');
    }

    public function test_create_employee_requires_valid_email(): void
    {
        $payload = $this->employeePayload(
            $this->advisorRoleId,
            ['email' => 'email-tidak-valid']
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertSessionHasErrors('email');
    }

    public function test_create_employee_rejects_duplicate_email(): void
    {
        $existingEmployee = $this->createEmployee(
            $this->mechanicRoleId,
            'existing'
        );

        $payload = $this->employeePayload(
            $this->advisorRoleId,
            ['email' => $existingEmployee->email]
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertSessionHasErrors('email');
    }

    public function test_create_employee_rejects_short_password(): void
    {
        $payload = $this->employeePayload(
            $this->advisorRoleId,
            [
                'password' => '1234567',
                'password_confirmation' => '1234567',
            ]
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertSessionHasErrors('password');
    }

    public function test_create_employee_rejects_password_confirmation_mismatch(): void
    {
        $payload = $this->employeePayload(
            $this->advisorRoleId,
            [
                'password' => 'password123',
                'password_confirmation' => 'password456',
            ]
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.create'))
            ->post(route('owner.employees.store'), $payload)
            ->assertSessionHasErrors('password');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT AND UPDATE
    |--------------------------------------------------------------------------
    */

    public function test_owner_can_open_edit_employee_page(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $response = $this->actingAs($this->owner)
            ->get(route('owner.employees.edit', $employee))
            ->assertOk()
            ->assertViewIs('owner.employees.edit')
            ->assertViewHas('user')
            ->assertViewHas('roles');

        $this->assertSame(
            $employee->id,
            $response->viewData('user')->id
        );
    }

    public function test_owner_can_update_employee_data(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $this->actingAs($this->owner)
            ->put(
                route('owner.employees.update', $employee),
                [
                    'name' => 'Mekanik Senior',
                    'email' => $employee->email,
                    'phone' => '081111222333',
                    'role_id' => $this->advisorRoleId,
                ]
            )
            ->assertRedirect(route('owner.employees.index'))
            ->assertSessionHas(
                'success',
                'Data pegawai berhasil diperbarui.'
            );

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'name' => 'Mekanik Senior',
            'email' => $employee->email,
            'phone' => '081111222333',
            'role_id' => $this->advisorRoleId,
        ]);
    }

    public function test_employee_can_keep_own_email_when_updated(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $originalPhone = $employee->phone;

        $this->actingAs($this->owner)
            ->put(
                route('owner.employees.update', $employee),
                [
                    'name' => 'Mekanik Diperbarui',
                    'email' => $employee->email,
                    'phone' => $originalPhone,
                    'role_id' => $this->mechanicRoleId,
                ]
            )
            ->assertRedirect(route('owner.employees.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'name' => 'Mekanik Diperbarui',
            'email' => $employee->email,
            'phone' => $originalPhone,
        ]);
    }

    public function test_update_employee_rejects_duplicate_email(): void
    {
        $firstEmployee = $this->createEmployee(
            $this->mechanicRoleId,
            'first'
        );

        $secondEmployee = $this->createEmployee(
            $this->advisorRoleId,
            'second'
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.edit', $secondEmployee))
            ->put(
                route('owner.employees.update', $secondEmployee),
                [
                    'name' => 'Nama Baru',
                    'email' => $firstEmployee->email,
                    'phone' => $secondEmployee->phone,
                    'role_id' => $this->advisorRoleId,
                ]
            )
            ->assertRedirect(
                route('owner.employees.edit', $secondEmployee)
            )
            ->assertSessionHasErrors('email');
    }

    public function test_update_employee_rejects_owner_role(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $this->actingAs($this->owner)
            ->from(route('owner.employees.edit', $employee))
            ->put(
                route('owner.employees.update', $employee),
                [
                    'name' => 'Mekanik',
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'role_id' => $this->ownerRoleId,
                ]
            )
            ->assertRedirect(
                route('owner.employees.edit', $employee)
            )
            ->assertSessionHasErrors('role_id');
    }

    public function test_owner_cannot_edit_another_owner_account(): void
    {
        $anotherOwner = $this->createUser(
            $this->ownerRoleId,
            'another-owner'
        );

        $this->actingAs($this->owner)
            ->get(route('owner.employees.edit', $anotherOwner))
            ->assertNotFound();
    }

    public function test_owner_cannot_update_customer_account_as_employee(): void
    {
        $customer = $this->createUser(
            $this->customerRoleId,
            'customer'
        );

        $this->actingAs($this->owner)
            ->put(
                route('owner.employees.update', $customer),
                [
                    'name' => 'Nama Baru',
                    'email' => 'customer.updated@example.test',
                    'phone' => $customer->phone,
                    'role_id' => $this->mechanicRoleId,
                ]
            )
            ->assertNotFound();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'email' => $customer->email,
            'role_id' => $this->customerRoleId,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    |
    | Nama route mengikuti kontrak aplikasi:
    | GET  : owner.employees.reset-password
    | PUT  : owner.employees.reset-password.update
    |
    | Jika nama route aplikasi berbeda, verifikasi terlebih dahulu
    | penggunaan route di Blade dan routes/web.php.
    |--------------------------------------------------------------------------
    */

    public function test_owner_can_open_reset_password_form(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $response = $this->actingAs($this->owner)
            ->get(
                route('owner.employees.reset-password', $employee)
            )
            ->assertOk()
            ->assertViewIs('owner.employees.reset-password')
            ->assertViewHas('user');

        $this->assertSame(
            $employee->id,
            $response->viewData('user')->id
        );
    }

    public function test_owner_can_reset_employee_password(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $newPassword = 'passwordBaru123';

        $this->actingAs($this->owner)
            ->put(
                route(
                    'owner.employees.reset-password.update',
                    $employee
                ),
                [
                    'password' => $newPassword,
                    'password_confirmation' => $newPassword,
                ]
            )
            ->assertRedirect(route('owner.employees.index'))
            ->assertSessionHas(
                'success',
                'Password pegawai berhasil direset.'
            );

        $employee->refresh();

        $this->assertTrue(
            Hash::check($newPassword, $employee->password)
        );

        $this->assertNotSame(
            $newPassword,
            $employee->password
        );
    }

    public function test_reset_password_rejects_short_password(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $this->actingAs($this->owner)
            ->from(
                route('owner.employees.reset-password', $employee)
            )
            ->put(
                route(
                    'owner.employees.reset-password.update',
                    $employee
                ),
                [
                    'password' => '1234567',
                    'password_confirmation' => '1234567',
                ]
            )
            ->assertSessionHasErrors('password');

        $employee->refresh();

        $this->assertTrue(
            Hash::check('password123', $employee->password)
        );
    }

    public function test_reset_password_rejects_confirmation_mismatch(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic'
        );

        $this->actingAs($this->owner)
            ->from(
                route('owner.employees.reset-password', $employee)
            )
            ->put(
                route(
                    'owner.employees.reset-password.update',
                    $employee
                ),
                [
                    'password' => 'passwordBaru123',
                    'password_confirmation' => 'passwordLain123',
                ]
            )
            ->assertSessionHasErrors('password');
    }

    public function test_owner_cannot_reset_customer_password_through_employee_feature(): void
    {
        $customer = $this->createUser(
            $this->customerRoleId,
            'customer'
        );

        $this->actingAs($this->owner)
            ->put(
                route(
                    'owner.employees.reset-password.update',
                    $customer
                ),
                [
                    'password' => 'passwordBaru123',
                    'password_confirmation' => 'passwordBaru123',
                ]
            )
            ->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function test_owner_can_deactivate_employee_account(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic',
            ['is_active' => true]
        );

        $this->actingAs($this->owner)
            ->patch(
                route('owner.employees.toggle-status', $employee)
            )
            ->assertRedirect(route('owner.employees.index'))
            ->assertSessionHas(
                'success',
                'Akun pegawai berhasil dinonaktifkan.'
            );

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'is_active' => false,
        ]);
    }

    public function test_owner_can_activate_employee_account(): void
    {
        $employee = $this->createEmployee(
            $this->mechanicRoleId,
            'mechanic',
            ['is_active' => false]
        );

        $this->actingAs($this->owner)
            ->patch(
                route('owner.employees.toggle-status', $employee)
            )
            ->assertRedirect(route('owner.employees.index'))
            ->assertSessionHas(
                'success',
                'Akun pegawai berhasil diaktifkan.'
            );

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'is_active' => true,
        ]);
    }

    public function test_owner_cannot_toggle_customer_account(): void
    {
        $customer = $this->createUser(
            $this->customerRoleId,
            'customer'
        );

        $this->actingAs($this->owner)
            ->patch(
                route('owner.employees.toggle-status', $customer)
            )
            ->assertNotFound();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'is_active' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION AND AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_access_employee_index(): void
    {
        $this->get(route('owner.employees.index'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_create_employee(): void
    {
        $payload = $this->employeePayload(
            $this->mechanicRoleId
        );

        $this->post(
            route('owner.employees.store'),
            $payload
        )->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', [
            'email' => 'pegawai.baru@example.test',
        ]);
    }

    public function test_non_owner_cannot_access_employee_index(): void
    {
        $mechanic = $this->createUser(
            $this->mechanicRoleId,
            'mechanic'
        );

        $this->actingAs($mechanic)
            ->get(route('owner.employees.index'))
            ->assertForbidden();
    }

    public function test_non_owner_cannot_create_employee(): void
    {
        $mechanic = $this->createUser(
            $this->mechanicRoleId,
            'mechanic'
        );

        $payload = $this->employeePayload(
            $this->advisorRoleId
        );

        $this->actingAs($mechanic)
            ->post(
                route('owner.employees.store'),
                $payload
            )
            ->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'email' => 'pegawai.baru@example.test',
        ]);
    }
}
