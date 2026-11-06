<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\VehicleInspection;
use App\Models\WorkOrder;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $identity = Str::lower(trim((string) $request->input('identity')));
            $ipAddress = (string) $request->ip();

            return [
                Limit::perMinute(5)->by('login-account:'.hash('sha256', $identity.'|'.$ipAddress)),
                Limit::perMinute(30)->by('login-ip:'.$ipAddress),
            ];
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(5)->by('register-ip:'.(string) $request->ip());
        });

        RateLimiter::for('guest-booking', function (Request $request) {
            return Limit::perMinute(5)
                ->by('guest-booking-ip:' . $request->ip());
        });

        // View::composer('components.service-advisor.sidebar', function ($view) {
        //     $pendingBookingsCount = Booking::where('status', 'PENDING')->count();

        //     // Inspeksi siap dibuatkan Work Order.
        //     $readyInspectionsCount = VehicleInspection::query()
        //         ->where('status', 'COMPLETED')
        //         ->whereDoesntHave('booking.workOrder')
        //         ->whereDoesntHave('recommendedWorks', function ($query) {
        //             $query->where('approval_status', 'PENDING');
        //         })
        //         ->count();

        //     // Work Order yang masih dalam proses pengerjaan.
        //     $monitoringWorkOrdersCount = WorkOrder::query()
        //         ->whereIn('status', [
        //             'RECEIVED',
        //             'INSPECTION',
        //             'DISASSEMBLY',
        //             'IN_PROGRESS',
        //             'ASSEMBLY',
        //             'FINAL_CHECK',
        //         ])
        //         ->count();

        //     $view->with([
        //         'pendingBookingsCount' => $pendingBookingsCount,
        //         'readyInspectionsCount' => $readyInspectionsCount,
        //         'monitoringWorkOrdersCount' => $monitoringWorkOrdersCount,
        //     ]);
        // });
    }
}
