<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identity' => ['required'],
            'password' => ['required'],
        ]);

        $identity = $credentials['identity'];

        $fieldType = filter_var($identity, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $loginData = [
            $fieldType => $identity,
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($loginData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role->name === 'Owner') {
                return redirect()->route('owner.dashboard');
            }

            if ($user->role->name === 'ServiceAdvisor') {
                return redirect()->route('service-advisor.dashboard');
            }

            if ($user->role->name === 'Mechanic') {
                return redirect()->route('mechanic.dashboard');
            }

            if ($user->role->name === 'Admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role->name === 'Customer') {
                return redirect()->route('customer.dashboard');
            }
        }

        return back()
            ->withErrors([
                'identity' => 'Email/Nomor telepon atau password salah.',
            ])
            ->onlyInput('identity');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
