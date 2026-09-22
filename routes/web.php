<?php

use App\Http\Controllers\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\VehicleController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Image\Transformations\Rotate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/', [AuthAuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthAuthController::class, 'login']);

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

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

    Route::middleware('role:Customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Profile
            Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

            Route::get('/profile/edit', [ProfileController::class, 'edit'])
                ->name('profile.edit');

            Route::put('/profile', [ProfileController::class, 'update'])
                ->name('profile.update');

            Route::get('/bookings', [BookingController::class, 'index'])
                ->name('bookings.index');

            Route::get('/booking-service', [BookingController::class, 'create'])
                ->name('bookingService');

            Route::post('/bookings', [BookingController::class, 'store'])
                ->name('bookings.store');

            Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
                ->name('bookings.cancel');

            Route::post('/bookings/{id}/review',[ReviewController::class, 'store'])
                ->name('bookings.review.store');

            // Vehicles
            Route::resource('vehicles', VehicleController::class);
            // Service Tracking
            Route::get('/tracking-service', [BookingController::class, 'tracking'])->name('trackingService');

            // Service History
            Route::get('/riwayat-service', [BookingController::class, 'history'])
                ->name('riwayatService');
        });
});

Route::post('/logout', [AuthAuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
