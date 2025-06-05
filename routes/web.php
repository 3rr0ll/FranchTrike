<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Operator\OperatorController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

Route::get('/', function () {
    return view('welcome');
});

// Default login page 
Route::view('/login', 'auth.login')->name('login')->middleware('guest');

// Custom login form for Admin and Superadmin
Route::get('admin/login', function () {
    return view('admins-login');
})->name('admin.login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Optional: shared fallback dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware([RoleMiddleware::class . ':operator'])
        ->prefix('operator')
        ->name('operator.')
        ->group(function () {
            Route::get('/home', [OperatorDashboard::class, 'index'])->name('home');

            // Manual CRUD routes to customize URI
            Route::get('/', [OperatorController::class, 'index'])->name('index');
            Route::get('/create', [OperatorController::class, 'create'])->name('create');
            Route::post('/', [OperatorController::class, 'store'])->name('store');
            Route::get('/{operator}/edit', [OperatorController::class, 'edit'])->name('edit');
            Route::put('/{operator}', [OperatorController::class, 'update'])->name('update');
            Route::delete('/{operator}', [OperatorController::class, 'destroy'])->name('destroy');
        });



    // Admin routes
    Route::middleware([RoleMiddleware::class . ':admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/home', [AdminDashboard::class, 'index'])->name('home');
            // Add more admin-specific routes here
        });

    // SuperAdmin routes
    Route::middleware([RoleMiddleware::class . ':superadmin'])
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/home', [SuperAdminDashboard::class, 'index'])->name('home');
            // Add more superadmin-specific routes here
        });
});
