<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return 'Dashboard';
    });

    Route::get('/owner/dashboard', function () {
        return 'Owner Dashboard';
    })->name('owner.dashboard')->middleware('role:Owner');

    Route::get('service-advisor/dashboard', function () {
        return 'Service Advisor Dashboard';
    })->name('service-advisor.dashboard')->middleware('role:ServiceAdvisor');

    Route::get('/mechanic/dashboard', function () {
        return 'Mechanic Dashboard';
    })->name('mechanic.dashboard')->middleware('role:Mechanic');

    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard';
    })->name('admin.dashboard')->middleware('role:Admin');

    Route::get('/customer/dashboard', function () {
        return 'Customer Dashboard';
    })->name('customer.dashboard')->middleware('role:Customer');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
