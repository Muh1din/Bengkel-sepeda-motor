<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Two\InvalidStateException;
use Tests\TestCase;

class GoogleOAuthStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_stores_state_in_a_fresh_session(): void
    {
        $oldSessionId = $this->app['session']->getId();

        $response = $this->get(route('google.redirect'));

        $response->assertRedirect();
        $response->assertCookie(config('session.cookie'));

        $this->assertNotSame($oldSessionId, $this->app['session']->getId());

        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY) ?? '', $query);

        $this->assertNotEmpty($query['state'] ?? null);
        $this->assertSame($query['state'], $this->app['session']->get('state'));
    }

    public function test_google_callback_rejects_a_mismatched_state(): void
    {
        $this->withoutExceptionHandling();
        $this->withSession(['state' => 'expected-state']);
        $this->expectException(InvalidStateException::class);

        $this->get(route('google.callback', ['state' => 'unexpected-state']));
    }
}
