<?php

use App\Http\Controllers\AuthController;
use Illuminate\Image\Transformations\Rotate;
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

    Route::middleware('role:Customer')->prefix('customer')->name('customer.')->group(function () {

        Route::get('/dashboard', function () {
            return view('customer.dashboard');
        })->name('dashboard');

        Route::get('/profile', function () {
            return view('customer.profile');
        })->name('profile');

        Route::get('kendaraan', function () {
            return view('customer.kendaraan');
        })->name('kendaraan');

        Route::get('booking-service', function () {
            return view('customer.bookingService');
        })->name('bookingService');

        Route::get('booking-aktif', function () {
            return view('customer.bookingAktif');
        })->name('bookingAktif');

        Route::get('tracking-service', function () {
            return view('customer.trackingService');
        })->name('trackingService');

        Route::get('riwayat-service', function () {
            return view('customer.riwayatService');
        })->name('riwayatService');

    });
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
