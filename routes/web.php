<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Operator\DriverController;
use App\Http\Controllers\Operator\DocumentSubmissionController;


use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\OperatorController as AdminOperatorController;
use App\Http\Controllers\Admin\DriverController as AdminDriverController;
use App\Http\Controllers\Admin\DocumentController;

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
            // Operator dashboard
            Route::get('/home', [OperatorDashboard::class, 'index'])->name('home');
            Route::get('/dashboard', [OperatorDashboard::class, 'index'])->name('dashboard');
            Route::get('/home', [OperatorDashboard::class, 'index'])->name('home');
            // Operator resource routes
            Route::get('/', [OperatorController::class, 'index'])->name('index');
            Route::get('/create', [OperatorController::class, 'create'])->name('create');
            Route::post('/', [OperatorController::class, 'store'])->name('store');
            Route::get('/{operator}/edit', [OperatorController::class, 'edit'])->name('edit');
            Route::put('/{operator}', [OperatorController::class, 'update'])->name('update');
            Route::delete('/{operator}', [OperatorController::class, 'destroy'])->name('destroy');

            // Driver resource routes (moved outside documents)
            Route::prefix('driver')->name('driver.')->group(function () {
                Route::get('/', [DriverController::class, 'index'])->name('index');
                Route::get('/create', [DriverController::class, 'create'])->name('create');
                Route::post('/', [DriverController::class, 'store'])->name('store');
                Route::get('/{driver}', [DriverController::class, 'show'])->name('show');
                Route::get('/{driver}/edit', [DriverController::class, 'edit'])->name('edit');
                Route::put('/{driver}', [DriverController::class, 'update'])->name('update');
                Route::delete('/{driver}', [DriverController::class, 'destroy'])->name('destroy');
            });

            // Document Submission Routes (moved to same level as driver)
            Route::prefix('documents')->name('documents.')->group(function () {

                // Operator Documents
                Route::prefix('operator')->name('operator.')->group(function () {
                    Route::get('/create', [DocumentSubmissionController::class, 'createOperatorDocuments'])
                        ->name('create');
                    Route::post('/store', [DocumentSubmissionController::class, 'storeOperatorDocuments'])
                        ->name('store');
                });



                // Document Status and Management
                Route::get('/status', [DocumentSubmissionController::class, 'viewDocumentStatus'])
                    ->name('status');
                Route::delete('/delete', [DocumentSubmissionController::class, 'deleteDocument'])
                    ->name('delete');



                // Driver Documents
                Route::prefix('driver')->name('driver.')->group(function () {
                    Route::get('/create/{driver?}', [DocumentSubmissionController::class, 'createDriverDocuments'])
                        ->name('create');
                    Route::post('/store', [DocumentSubmissionController::class, 'storeDriverDocuments'])
                        ->name('store');
                });
            });
        });


    // Admin routes
    Route::middleware([RoleMiddleware::class . ':admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/home', [AdminDashboard::class, 'index'])->name('home');

            Route::get('/operators', [AdminOperatorController::class, 'index'])->name('operators.index');
            Route::get('/drivers', [AdminDriverController::class, 'index'])->name('drivers.index');

            Route::get('/documents/operator/{operator}', [DocumentController::class, 'viewOperatorDocuments'])->name('documents.operator.show');
            Route::get('/documents/driver/{driver}', [DocumentController::class, 'viewDriverDocuments'])->name('documents.driver.show');
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
