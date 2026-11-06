<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $customer = $user->customer;

        if (! $customer || blank($customer->phone)) {
            return redirect()
                ->back()
                ->with('profile_incomplete', true);
        }

        return $next($request);
    }
}
