<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        $role = Role::where('name', 'Owner')->firstOrFail();

        return User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test-owner@gmail.com',
            'password' => 'rahasia',
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createUser();

        $response = $this->post('/login', [
            'identity' => 'test-owner@gmail.com',
            'password' => 'rahasia',
        ]);

        $response->assertRedirect(
            route('owner.dashboard')
        );

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = $this->createUser();

        $response = $this->post('/login', [
            'identity' => 'test-owner@gmail.com',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors('identity');

        $this->assertGuest();
    }

    public function test_user_cannot_login_with_unregistered_email(): void
    {
        $response = $this->post('/login', [
            'identity' => 'tidakada@gmail.com',
            'password' => 'rahasia',
        ]);

        $response->assertSessionHasErrors('identity');

        $this->assertGuest();
    }

    public function test_social_account_without_password_cannot_use_password_login(): void
    {
        $user = User::factory()->withoutPassword()->create([
            'role_id' => Role::where('name', 'Customer')->firstOrFail()->id,
            'email' => 'social-only@example.com',
        ]);

        $response = $this->post('/login', [
            'identity' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('identity');
        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_attempts_for_the_same_identity(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post('/login', [
                'identity' => 'missing@example.com',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('identity');
        }

        $this->post('/login', [
            'identity' => 'missing@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    public function test_user_can_link_a_social_account_to_their_user_record(): void
    {
        $user = $this->createUser();

        $socialAccount = $user->socialAccounts()->create([
            'provider' => 'google',
            'provider_user_id' => 'google-subject-123',
        ]);

        $this->assertSame($user->id, $socialAccount->user->id);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => 'google-subject-123',
        ]);
    }

    public function test_social_provider_identity_cannot_be_linked_to_two_users(): void
    {
        $firstUser = $this->createUser();
        $firstUser->socialAccounts()->create([
            'provider' => 'apple',
            'provider_user_id' => 'apple-subject-123',
        ]);

        $secondUser = User::factory()->create([
            'role_id' => Role::where('name', 'Customer')->firstOrFail()->id,
            'email' => 'second-user@example.com',
        ]);

        try {
            $secondUser->socialAccounts()->create([
                'provider' => 'apple',
                'provider_user_id' => 'apple-subject-123',
            ]);

            $this->fail('A provider identity must belong to only one user.');
        } catch (UniqueConstraintViolationException) {
            $this->assertDatabaseCount('social_accounts', 1);
        }
    }

    public function test_session_is_regenerated_after_login(): void
    {
        $user = $this->createUser();

        $oldSessionId = $this->app['session']->getId();

        $this->post('/login', [
            'identity' => 'test-owner@gmail.com',
            'password' => 'rahasia',
        ]);

        $newSessionId = $this->app['session']->getId();

        $this->assertNotSame(
            $oldSessionId,
            $newSessionId
        );

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(
            route('login')
        );

        $this->assertGuest();
    }

    public function test_guest_cannot_access_authenticated_page(): void
    {
        $response = $this->get('/owner/dashboard');

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
