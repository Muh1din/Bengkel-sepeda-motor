<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Material;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_bookings' => Booking::count(),

            'pending_bookings' => Booking::where(
                'status',
                'PENDING'
            )->count(),

            'active_work_orders' => WorkOrder::whereNotNull(
                'received_at'
            )->whereNull('completed_at')->count(),

            'completed_work_orders' => WorkOrder::whereNotNull(
                'completed_at'
            )->count(),

            'total_materials' => Material::count(),

            'low_stock_materials' => Material::whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            )->count(),

            'out_of_stock_materials' => Material::where(
                'stock',
                0
            )->count(),
        ];

        $recentWorkOrders = WorkOrder::with([
            'booking.vehicle',
            'mechanic',
        ])
            ->latest('id')
            ->take(5)
            ->get();

        // Rentang tujuh hari terakhir, termasuk hari ini.
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today()->endOfDay();

        // Grafik Booking Masuk.
        $bookingData = Booking::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        // Grafik Work Order Selesai.
        $completedData = WorkOrder::query()
            ->selectRaw('DATE(completed_at) as date, COUNT(*) as total')
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->pluck('total', 'date');

        // Siapkan label dan nilai untuk grafik.
        $chartLabels = [];
        $bookingChartData = [];
        $completedChartData = [];

        for ($date = $startDate->copy(); $date->lte(Carbon::today()); $date->addDay()) {
            $key = $date->toDateString();

            $chartLabels[] = $date->translatedFormat('d M');
            $bookingChartData[] = (int) ($bookingData[$key] ?? 0);
            $completedChartData[] = (int) ($completedData[$key] ?? 0);
        }

        // Distribusi status Work Order.
        // Distribusi status Booking.
        $bookingStatusData = Booking::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('owner.dashboard', compact(
            'statistics',
            'recentWorkOrders',
            'chartLabels',
            'bookingChartData',
            'completedChartData',
            'bookingStatusData',
        ));
    }
}
