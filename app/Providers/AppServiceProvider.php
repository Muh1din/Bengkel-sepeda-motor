<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\WorkOrder;
use App\Models\VehicleInspection;
use Illuminate\Support\Facades\View;
use App\Models\Booking;

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
