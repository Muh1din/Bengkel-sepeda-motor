<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        $role = Role::create([
            'name' => 'Owner',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
            'password' => 'rahasia',
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createUser();

        $user->update([
            'email' => 'owner@gmail.com',
        ]);

        $response = $this->post('/login', [
            'identity' => 'owner@gmail.com',
            'password' => 'rahasia',
        ]);

        $response->assertRedirect(route('owner.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = $this->createUser();

        $user->update([
            'email' => 'owner@gmail.com',
        ]);

        $response = $this->post('/login', [
            'identity' => 'owner@gmail.com',
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

    public function test_session_is_regenerated_after_login(): void
    {
        $user = $this->createUser();

        $user->update([
            'email' => 'owner@gmail.com',
        ]);

        $oldSessionId = $this->app['session']->getId();

        $this->post('/login', [
            'identity' => 'owner@gmail.com',
            'password' => 'rahasia',
        ]);

        $newSessionId = $this->app['session']->getId();

        $this->assertNotSame($oldSessionId, $newSessionId);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_guest_cannot_access_authenticated_page(): void
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect(route('login'));
        
        $this->assertGuest();
    }
}
