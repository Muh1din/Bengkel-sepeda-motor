<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationDatabaseConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_user_id_is_unique(): void
    {
        $user = User::factory()->create();

        Customer::create([
            'name' => 'Linked Customer One',
            'email' => 'linked-one@example.com',
            'phone' => '081234567801',
            'user_id' => $user->id,
        ]);

        try {
            Customer::create([
                'name' => 'Linked Customer Two',
                'email' => 'linked-two@example.com',
                'phone' => '081234567802',
                'user_id' => $user->id,
            ]);

            $this->fail('A user must have at most one linked customer record.');
        } catch (UniqueConstraintViolationException) {
            $this->assertSame(
                1,
                Customer::where('user_id', $user->id)->count()
            );
        }
    }

    public function test_multiple_guest_customers_can_have_null_user_id(): void
    {
        foreach (['one', 'two'] as $guest) {
            Customer::create([
                'name' => 'Guest '.$guest,
                'email' => 'guest-'.$guest.'@example.com',
                'phone' => '08123456780'.($guest === 'one' ? '3' : '4'),
                'user_id' => null,
            ]);
        }

        $this->assertSame(
            2,
            Customer::whereNull('user_id')
                ->whereIn('email', ['guest-one@example.com', 'guest-two@example.com'])
                ->count()
        );
    }

    public function test_social_provider_is_lowercase_and_subject_is_case_sensitive(): void
    {
        $user = User::factory()->create();

        $user->socialAccounts()->create([
            'provider' => 'GoOgLe',
            'provider_user_id' => 'Subject-AbC',
        ]);

        $user->socialAccounts()->create([
            'provider' => 'GOOGLE',
            'provider_user_id' => 'subject-abc',
        ]);

        $this->assertSame(2, $user->socialAccounts()->count());
        $this->assertDatabaseHas('social_accounts', [
            'provider' => 'google',
            'provider_user_id' => 'Subject-AbC',
        ]);

        try {
            $user->socialAccounts()->create([
                'provider' => 'google',
                'provider_user_id' => 'Subject-AbC',
            ]);

            $this->fail('A provider subject must be unique for its provider.');
        } catch (UniqueConstraintViolationException) {
            $this->assertSame(2, $user->socialAccounts()->count());
        }
    }
}
