<?php

use App\Http\Controllers\Admin\BookingSettingController;
use App\Http\Controllers\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\PortfolioDashboardController;
use App\Http\Controllers\Home\ProjectController;
use App\Http\Controllers\Home\TrackingController;
use App\Http\Controllers\Admin\InventoryDashboardController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\RequestRejectionController;
use App\Http\Controllers\Admin\RequestFulfillmentController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockOutController;
use App\Http\Controllers\Admin\StockInController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MaterialRequestController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Mechanic\MechanicWorkOrderController;
use App\Http\Controllers\ServiceAdvisor\WorkOrderController;
use App\Http\Controllers\ServiceAdvisor\RecommendedWorkController;
use App\Http\Controllers\Mechanic\InspectionController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\VehicleController;
use App\Http\Controllers\Home\HomeBookingController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardController;
use App\Http\Controllers\Mechanic\RequestItemController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\InventoryController;
use App\Http\Controllers\Owner\ItemUsageReportController;
use App\Http\Controllers\Owner\VehicleActivityController;
use App\Http\Controllers\Owner\WorkOrderController as OwnerWorkOrderController;
use App\Http\Controllers\Owner\WorkOrderReportController;
use App\Http\Controllers\ServiceAdvisor\BookingController as ServiceAdvisorBookingController;
use App\Http\Controllers\ServiceAdvisor\CustomerController;
use App\Http\Controllers\ServiceAdvisor\Dashboard;
use App\Http\Controllers\ServiceAdvisor\DashboardController as ServiceAdvisorDashboardController;
use App\Http\Controllers\ServiceAdvisor\InspectionController as ServiceAdvisorInspectionController;
use Illuminate\Image\Transformations\Rotate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// mock up

Route::get('/mockup-login', function () {
    return "public/design/auth/login.html";
});


// Guest
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/{slug}', [ProjectController::class, 'show'])
    ->name('projects.show');

// Route::get('/projects/{slug}', [ProjectController::class, 'show'])
//     ->name('projects.show');

// Route::view('/projects', 'projects.index')->name('projects.index');

// tracking servis
Route::post('/tracking', [TrackingController::class, 'search'])
    ->name('guest.tracking.search');

Route::get('/booking-form', [HomeBookingController::class, 'index'])
    ->name('booking.form.index');

Route::post('/booking-form', [HomeBookingController::class, 'store'])
    ->name('booking.form.store');

Route::get('/login', [AuthAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthAuthController::class, 'login']);

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsActive::class,])->group(function () {

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

            // Bookings
            Route::get('/bookings', [BookingController::class, 'index'])
                ->name('bookings.index');

            Route::get('/booking-service', [BookingController::class, 'create'])
                ->name('bookingService');

            Route::post('/bookings', [BookingController::class, 'store'])
                ->name('bookings.store');

            Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
                ->name('bookings.cancel');

            Route::post('/bookings/{id}/review', [ReviewController::class, 'store'])
                ->name('bookings.review.store');

            // Vehicles
            Route::resource('vehicles', VehicleController::class);
            // Service Tracking
            Route::get('/tracking-service', [BookingController::class, 'tracking'])->name('trackingService');

            // Service History
            Route::get('/riwayat-service', [BookingController::class, 'history'])
                ->name('riwayatService');
        });

    Route::middleware('role:ServiceAdvisor')
        ->prefix('service-advisor')
        ->name('service-advisor.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [ServiceAdvisorDashboardController::class, 'index'])->name('dashboard');

            // Booking
            Route::get('/bookings', [ServiceAdvisorBookingController::class, 'index'])->name('bookings');

            Route::patch('/bookings/{booking}/confirm', [ServiceAdvisorBookingController::class, 'confirm'])
                ->name('bookings.confirm');

            Route::patch('/bookings/{booking}/reject', [ServiceAdvisorBookingController::class, 'reject'])
                ->name('bookings.reject');

            Route::patch('/bookings/{booking}/receive', [ServiceAdvisorBookingController::class, 'receive'])
                ->name('bookings.receive');

            // Work order
            Route::get('/work-orders', [WorkOrderController::class, 'index'])
                ->name('work-orders.index');

            Route::post('/work-orders/{inspection}', [WorkOrderController::class, 'store'])
                ->name('work-orders.store');

            // Inspection Result
            Route::get('/inspections', [ServiceAdvisorInspectionController::class, 'index'])
                ->name('inspections.index');

            Route::get('/inspections/{inspection}', [ServiceAdvisorInspectionController::class, 'show'])
                ->name('inspections.show');

            // Recommended Work
            Route::patch(
                '/recommended-works/{recommendedWork}/approve',
                [RecommendedWorkController::class, 'approve']
            )->name('recommended-works.approve');

            Route::patch(
                '/recommended-works/{recommendedWork}/reject',
                [RecommendedWorkController::class, 'reject']
            )->name('recommended-works.reject');

            // Monitoring
            Route::get('/monitoring', [WorkOrderController::class, 'monitoringIndex'])
                ->name('monitoring.index');

            Route::get('/monitoring/{workOrder}', [WorkOrderController::class, 'monitoring'])
                ->name('monitoring.show');

            Route::get(
                '/work-orders/{workOrder}/monitoring',
                [WorkOrderController::class, 'monitoring']
            )
                ->name('work-orders.monitoring');

            // Customer 
            Route::get('/customer', [CustomerController::class, 'index'])
                ->name('customer.index');

            Route::get('/customer/{customer}', [CustomerController::class, 'show'])
                ->name('customer.show');
        });

    Route::middleware('role:Mechanic')
        ->prefix('mechanic')
        ->name('mechanic.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [MechanicDashboardController::class, 'index'])
                ->name('dashboard');

            // Insepection
            Route::get('/inspections', [InspectionController::class, 'index'])
                ->name('inspections.index');

            Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])
                ->name('inspections.show');

            Route::patch('/inspections/{inspection}/draft', [InspectionController::class, 'saveDraft'])
                ->name('inspections.save-draft');

            Route::patch('/inspections/{inspection}/complete', [InspectionController::class, 'complete'])
                ->name('inspections.complete');

            // Work Order
            Route::get('/work-orders', [MechanicWorkOrderController::class, 'index'])
                ->name('work-orders.index');

            Route::get('/work-orders/{workOrder}', [MechanicWorkOrderController::class, 'show'])
                ->name('work-orders.show');

            Route::patch('/work-items/{workItem}/start', [MechanicWorkOrderController::class, 'start'])
                ->name('work-items.start');

            Route::patch('/work-items/{workItem}/complete', [MechanicWorkOrderController::class, 'complete'])
                ->name('work-items.complete');

            // Request Item
            Route::get('/request-item', [RequestItemController::class, 'index'])
                ->name('request-item.index');

            Route::post('/request-item', [RequestItemController::class, 'store'])
                ->name('request-item.store');
        });

    Route::middleware('role:Admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Portfolio Dashboard
            Route::get('/portfolio', [PortfolioDashboardController::class, 'index'])
                ->name('portfolio.dashboard');

            // Portfolio Projects
            Route::get('/projects', [AdminProjectController::class, 'index'])
                ->name('projects.index');

            Route::get('/projects/create', [AdminProjectController::class, 'create'])
                ->name('projects.create');

            Route::post('/projects', [AdminProjectController::class, 'store'])
                ->name('projects.store');

            Route::get('/projects/{project}/edit', [AdminProjectController::class, 'edit'])
                ->name('projects.edit');

            Route::put('/projects/{project}', [AdminProjectController::class, 'update'])
                ->name('projects.update');

            Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])
                ->name('projects.destroy');

            // Project Categories
            Route::get('/project-categories', [
                ProjectCategoryController::class,
                'index',
            ])->name('project-categories.index');

            Route::get('/project-categories/create', [
                ProjectCategoryController::class,
                'create',
            ])->name('project-categories.create');

            Route::post('/project-categories', [
                ProjectCategoryController::class,
                'store',
            ])->name('project-categories.store');

            Route::get('/project-categories/{projectCategory}/edit', [
                ProjectCategoryController::class,
                'edit',
            ])->name('project-categories.edit');

            Route::put('/project-categories/{projectCategory}', [
                ProjectCategoryController::class,
                'update',
            ])->name('project-categories.update');

            Route::delete('/project-categories/{projectCategory}', [
                ProjectCategoryController::class,
                'destroy',
            ])->name('project-categories.destroy');

            // Review
            Route::get('/reviews', [AdminReviewController::class, 'index'])
                ->name('reviews.index');

            Route::patch('/reviews/{review}/publish', [AdminReviewController::class, 'publish'])
                ->name('reviews.publish');

            Route::patch('/reviews/{review}/hide', [AdminReviewController::class, 'hide'])
                ->name('reviews.hide');

            // Dashboard
            Route::get('/dashboard', [InventoryDashboardController::class, 'index'])
                ->name('dashboard');

            // Materials
            Route::get('/materials', [MaterialController::class, 'index'])
                ->name('materials.index');

            Route::get('/materials/create', [MaterialController::class, 'create'])
                ->name('materials.create');

            Route::post('/materials', [MaterialController::class, 'store'])
                ->name('materials.store');

            Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])
                ->name('materials.edit');

            Route::put('/materials/{material}', [MaterialController::class, 'update'])
                ->name('materials.update');

            Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])
                ->name('materials.destroy');

            // Category
            Route::get('/categories', [CategoryController::class, 'index'])
                ->name('categories.index');

            Route::get('/categories/create', [CategoryController::class, 'create'])
                ->name('categories.create');

            Route::post('/categories', [CategoryController::class, 'store'])
                ->name('categories.store');

            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
                ->name('categories.edit');

            Route::put('/categories/{category}', [CategoryController::class, 'update'])
                ->name('categories.update');

            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
                ->name('categories.destroy');

            // Material Request
            Route::get('/material-request', [MaterialRequestController::class, 'index'])
                ->name('material-requests.index');

            Route::post('/material-requests/{materialRequest}/fulfill', [RequestFulfillmentController::class, 'store'])
                ->name('material-requests.fulfill');

            Route::post('/material-requests/{materialRequest}/reject', [RequestRejectionController::class, 'store',])
                ->name('material-requests.reject');

            // Stock-in
            Route::get('/stock-in', [StockInController::class, 'index'])
                ->name('stock-in.index');

            Route::post('/stock-in', [StockInController::class, 'store'])
                ->name('stock-in.store');

            // Stock-out
            Route::get('/stock-out', [StockOutController::class, 'index'])
                ->name('stock-out.index');

            // Stock adjustment
            Route::get('/stock-adjustments', [StockAdjustmentController::class, 'index'])
                ->name('stock-adjustments.index');

            Route::post('/stock-adjustments', [StockAdjustmentController::class, 'store'])
                ->name('stock-adjustments.store');

            // Stock Movement
            Route::get('/stock-movements', [StockMovementController::class, 'index'])
                ->name('stock-movements.index');

            // Booking setting
            Route::get('/booking-settings', [BookingSettingController::class, 'edit'])
                ->name('booking-settings.edit');

            Route::put('/booking-settings', [BookingSettingController::class, 'update'])
                ->name('booking-settings.update');
        });

    Route::middleware(['auth', 'role:Owner'])
        ->prefix('owner')
        ->name('owner.')
        ->group(function () {

            Route::get('/dashboard', [OwnerDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/bookings', [OwnerBookingController::class, 'index'])
                ->name('bookings.index');

            Route::get('/work-orders', [OwnerWorkOrderController::class, 'index'])
                ->name('work-orders.index');

            Route::get('/vehicle-activities', [VehicleActivityController::class, 'index'])
                ->name('vehicle-activities.index');

            Route::get('/inventory', [InventoryController::class, 'index'])
                ->name('inventory.index');

            Route::get('/reports/work-orders', [WorkOrderReportController::class, 'index'])
                ->name('reports.work-orders');

            Route::get('/reports/item-usage', [ItemUsageReportController::class, 'index'])
                ->name('reports.item-usage');

            // Employe
            Route::get('/employees', [EmployeeController::class, 'index',])
                ->name('employees.index');

            Route::get('/employees/create', [
                EmployeeController::class,
                'create',
            ])->name('employees.create');

            Route::post('/employees', [EmployeeController::class, 'store',])
                ->name('employees.store');

            Route::get('/employees/{user}/edit', [EmployeeController::class, 'edit',])
                ->name('employees.edit');

            Route::put('/employees/{user}', [EmployeeController::class, 'update',])
                ->name('employees.update');

            // reset password
            Route::get('/employees/{user}/reset-password', [
                EmployeeController::class,
                'resetPasswordForm',
            ])->name('employees.reset-password');

            Route::put('/employees/{user}/reset-password', [
                EmployeeController::class,
                'resetPassword',
            ])->name('employees.reset-password.update');

            // Toggle status
            Route::patch('/employees/{user}/toggle-status', [EmployeeController::class, 'toggleStatus',])
                ->name('employees.toggle-status');
        });
});

Route::post('/logout', [AuthAuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
