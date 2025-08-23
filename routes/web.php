<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Operator\DriverController;
use App\Http\Controllers\Operator\DocumentSubmissionController;
use App\Http\Controllers\Operator\FranchiseApplicationController;
use App\Http\Controllers\Operator\MotorChangeController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\OperatorController as AdminOperatorController;
use App\Http\Controllers\Admin\DriverController as AdminDriverController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FranchiseApplicationController as AdminFranchiseController;
use App\Http\Controllers\Admin\MotorDetailsController;
use App\Http\Controllers\Admin\MotorChangeApprovalController;

use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

Route::get('/', function () {
    return view('welcome');
});

// Default login page 
Route::view('/login', 'auth.login')->name('login')->middleware('guest');

// Admin and Superadmin login form (now in auth folder)
Route::view('admin/login', 'auth.admins-login')->name('admin.login')->middleware('guest');

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

            // Operator resource routes
            Route::get('/', [OperatorController::class, 'index'])->name('index');
            Route::get('/create', [OperatorController::class, 'create'])->name('create');
            Route::post('/', [OperatorController::class, 'store'])->name('store');
            Route::get('/{operator}/edit', [OperatorController::class, 'edit'])->name('edit');
            Route::put('/{operator}', [OperatorController::class, 'update'])->name('update');
            Route::delete('/{operator}', [OperatorController::class, 'destroy'])->name('destroy');

            Route::prefix('franchise')->name('franchise.')->group(function () {
                Route::get('/', [FranchiseApplicationController::class, 'index'])->name('index');
                Route::get('/create', [FranchiseApplicationController::class, 'create'])->name('create');
                Route::post('/', [FranchiseApplicationController::class, 'store'])->name('store');
                Route::get('/{franchiseApplication}/motor-details', [FranchiseApplicationController::class, 'motorDetails'])->name('motor-details');
                Route::post('/{franchiseApplication}/motor-details', [FranchiseApplicationController::class, 'storeMotorDetails'])->name('store-motor-details');
                Route::get('motor-change/{franchise}', [MotorChangeController::class, 'create'])->name('motor-change.create');
                Route::post('motor-change/{franchise}', [MotorChangeController::class, 'store'])->name('motor-change.store');
                Route::get('/{franchiseApplication}', [FranchiseApplicationController::class, 'show'])->name('show');
            });

            Route::prefix('driver')->name('driver.')->group(function () {
                Route::get('/', [DriverController::class, 'index'])->name('index');
                Route::get('/create', [DriverController::class, 'create'])->name('create');
                Route::post('/', [DriverController::class, 'store'])->name('store');
                Route::get('/{driver}', [DriverController::class, 'show'])->name('show');
                Route::get('/{driver}/edit', [DriverController::class, 'edit'])->name('edit');
                Route::put('/{driver}', [DriverController::class, 'update'])->name('update');
                Route::delete('/{driver}', [DriverController::class, 'destroy'])->name('destroy');
            });

            // If /operator/driver/create is not found, show a 404 page
            // (Handled by Laravel automatically if no route matches)
            // If you want a custom 404, you can define a fallback route:
            // Route::fallback(function () {
            //     abort(404, 'Page not found');
            // });

            // Document Submission Routes 
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

            // Payment Routes
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Operator\PaymentController::class, 'index'])->name('index');
                Route::get('/history', [\App\Http\Controllers\Operator\PaymentController::class, 'history'])->name('history');
                Route::get('/success', [\App\Http\Controllers\Operator\PaymentController::class, 'success'])->name('success');
                Route::get('/cancel', [\App\Http\Controllers\Operator\PaymentController::class, 'cancel'])->name('cancel');
                Route::get('/receipt/{payment}', [\App\Http\Controllers\Operator\PaymentController::class, 'receipt'])->name('receipt');
                Route::get('/{fee}', [\App\Http\Controllers\Operator\PaymentController::class, 'show'])->name('show');
                Route::post('/{fee}', [\App\Http\Controllers\Operator\PaymentController::class, 'createPayment'])->name('create');
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

            // Franchise Applications Routes
            Route::get('/franchise', [AdminFranchiseController::class, 'index'])->name('franchise.index');
            Route::get('/franchise/{franchiseApplication}', [AdminFranchiseController::class, 'show'])->name('franchise.show');
            Route::put('/franchise/{franchiseApplication}/status', [AdminFranchiseController::class, 'updateStatus'])->name('franchise.update-status');
            Route::post('/franchise/bulk-update', [AdminFranchiseController::class, 'bulkUpdateStatus'])->name('franchise.bulk-update');
            Route::get('/franchise/statistics', [AdminFranchiseController::class, 'statistics'])->name('franchise.statistics');
            Route::get('/franchise/export', [AdminFranchiseController::class, 'export'])->name('franchise.export');

            // Motor Details Routes
            Route::get('/motor-details', [MotorDetailsController::class, 'index'])->name('motor-details.index');
            Route::get('/motor-details/{motorDetail}', [MotorDetailsController::class, 'show'])->name('motor-details.show');
            Route::get('/motor-details/{motorDetail}/edit', [MotorDetailsController::class, 'edit'])->name('motor-details.edit');
            Route::put('/motor-details/{motorDetail}', [MotorDetailsController::class, 'update'])->name('motor-details.update');
            Route::delete('/motor-details/{motorDetail}', [MotorDetailsController::class, 'destroy'])->name('motor-details.destroy');
            Route::post('/motor-details/bulk-update', [MotorDetailsController::class, 'bulkUpdate'])->name('motor-details.bulk-update');
            Route::get('/motor-details/statistics', [MotorDetailsController::class, 'statistics'])->name('motor-details.statistics');
            Route::get('/motor-details/export', [MotorDetailsController::class, 'export'])->name('motor-details.export');



            Route::get('/documents/operator/{operator}', [DocumentController::class, 'viewOperatorDocuments'])->name('documents.operator.show');
            Route::get('/documents/driver/{driver}', [DocumentController::class, 'viewDriverDocuments'])->name('documents.driver.show');

            Route::post('/documents/operator/{document}/verify', [DocumentController::class, 'verifyOperatorDocument'])
                ->name('documents.operator.verify');
            Route::post('/documents/driver/{document}/verify', [DocumentController::class, 'verifyDriverDocument'])
                ->name('documents.driver.verify');

            // Motor Change Approval Routes
            Route::get('motor-change', [MotorChangeApprovalController::class, 'index'])->name('motor-change.index');
            Route::post('motor-change/{motorChange}/approve', [MotorChangeApprovalController::class, 'approve'])->name('motor-change.approve');
            Route::post('motor-change/{motorChange}/reject', [MotorChangeApprovalController::class, 'reject'])->name('motor-change.reject');
        });


    // SuperAdmin routes
    Route::middleware([RoleMiddleware::class . ':superadmin'])
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/home', [SuperAdminDashboard::class, 'index'])->name('home');
            Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

            // Security Settings Routes (must come before resource routes)
            Route::get('/users/security-settings', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'securitySettings'])->name('users.security-settings');
            Route::put('/users/security-settings', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'updateSecuritySettings'])->name('users.update-security-settings');

            // Test route for debugging
            Route::get('/test-superadmin', function () {
                return 'Superadmin access working!';
            })->name('test.superadmin');

            // User Management Routes
            Route::resource('users', \App\Http\Controllers\SuperAdmin\UserManagementController::class);
            Route::get('/users/{user}/password-reset', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'showPasswordReset'])->name('users.password-reset');
            Route::put('/users/{user}/password-reset', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'resetPassword'])->name('users.reset-password');
            Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::get('/users/statistics', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'statistics'])->name('users.statistics');

            // Login Security Routes
            Route::patch('/users/{user}/reset-attempts', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'resetLoginAttempts'])->name('users.reset-attempts');
            Route::patch('/users/{user}/lock', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'lockAccount'])->name('users.lock');
            Route::patch('/users/{user}/unlock', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'unlockAccount'])->name('users.unlock');
            Route::patch('/users/{user}/force-logout', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'forceLogout'])->name('users.force-logout');
            Route::get('/users/{user}/login-history', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'loginHistory'])->name('users.login-history');
            Route::get('/users/login-logs', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'allLoginLogs'])->name('users.login-logs');

            // Global Search
            Route::get('/search', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'search'])->name('search');

            // Payment Management Routes
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'store'])->name('store');
                Route::get('/{fee}/edit', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'edit'])->name('edit');
                Route::put('/{fee}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'update'])->name('update');
                Route::delete('/{fee}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'destroy'])->name('destroy');

                // Payment Records Management
                Route::get('/records', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'payments'])->name('records');
                Route::post('/records', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'createPayment'])->name('create-payment');
                Route::put('/records/{payment}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'updatePayment'])->name('update-payment');
                Route::delete('/records/{payment}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'destroyPayment'])->name('destroy-payment');

                // Statistics
                Route::get('/statistics', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'statistics'])->name('statistics');
            });
        });
});
