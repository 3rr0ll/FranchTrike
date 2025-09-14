<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\Admin\PaymentController;


use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

// Default login page 
Route::view('/login', 'auth.login')->name('login')->middleware('guest');

// Admin and Superadmin login form (now in auth folder)
Route::view('admin/login', 'auth.admins-login')->name('admin.login')->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        // Determine dashboard view based on user type/role
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('operator')) {
                return redirect()->route('operator.dashboard');
            } elseif ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('superadmin')) {
                return redirect()->route('superadmin.dashboard');
            }
        } elseif (property_exists($user, 'usertype')) {
            switch ($user->usertype) {
                case 'operator':
                    return redirect()->route('operator.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard');
            }
        }
        // Default fallback
        return view('welcome');
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

            Route::prefix('franchise')->name('franchise.')->group(function () {
                Route::get('/', [FranchiseApplicationController::class, 'index'])->name('index');
                Route::get('/create', [FranchiseApplicationController::class, 'create'])->name('create');
                Route::post('/', [FranchiseApplicationController::class, 'store'])->name('store');
                Route::get('/{franchiseApplication}/motor-details', [FranchiseApplicationController::class, 'motorDetails'])->name('motor-details');
                Route::post('/{franchiseApplication}/motor-details', [FranchiseApplicationController::class, 'storeMotorDetails'])->name('store-motor-details');
                Route::get('/{franchiseApplication}', [FranchiseApplicationController::class, 'show'])->name('show');
                Route::post('/{franchiseApplication}/renew', [FranchiseApplicationController::class, 'renew'])->name('renew');
                Route::get('motor-change/{franchise}', [MotorChangeController::class, 'create'])->name('motor-change.create');
                Route::post('motor-change/{franchise}', [MotorChangeController::class, 'store'])->name('motor-change.store');
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

            // Operator settings page (password change)
            Route::get('/settings', function () {
                return view('operator.settings');
            })->name('settings');

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
                Route::post('/resume/{payment}', [\App\Http\Controllers\Operator\PaymentController::class, 'resume'])->name('resume');

                // Pay All functionality (must come before /{fee} route)
                Route::get('/pay-all', [\App\Http\Controllers\Operator\PaymentController::class, 'payAll'])->name('pay-all');
                Route::post('/pay-all', [\App\Http\Controllers\Operator\PaymentController::class, 'createPayAllPayment'])->name('create-pay-all');
                Route::get('/pay-all/receipt/{paymentIntentId}', [\App\Http\Controllers\Operator\PaymentController::class, 'payAllReceipt'])->name('pay-all.receipt');

                Route::get('/{fee}', [\App\Http\Controllers\Operator\PaymentController::class, 'show'])->name('show');
                Route::post('/{fee}', [\App\Http\Controllers\Operator\PaymentController::class, 'createPayment'])->name('create');
            });

            // Notifications: mark all as read
            Route::patch('/notifications/read', function () {
                $user = \Illuminate\Support\Facades\Auth::user();
                if ($user) {
                    $user->siteNotifications()->whereNull('read_at')->update(['read_at' => now()]);
                }
                return response()->noContent();
            })->name('notifications.read');
        });

    // Admin routes
    Route::middleware(['auth', RoleMiddleware::class . ':admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/home', [AdminDashboard::class, 'index'])->name('home');

            Route::get('/operators', [AdminOperatorController::class, 'index'])->name('operators.index');
            Route::get('/drivers', [AdminDriverController::class, 'index'])->name('drivers.index');

            // Franchise Applications Routes
            Route::get('/franchise', [AdminFranchiseController::class, 'index'])->name('franchise.index');
            Route::get('/franchise/create', [AdminFranchiseController::class, 'create'])->name('franchise.create');
            Route::post('/franchise', [AdminFranchiseController::class, 'store'])->name('franchise.store');
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
            Route::get('motor-change/{motorChange}/input-details', [MotorChangeApprovalController::class, 'inputDetails'])->name('motor-change.input-details');
            Route::post('motor-change/{motorChange}/input-details', [MotorChangeApprovalController::class, 'storeNewDetails'])->name('motor-change.input-details');
            Route::post('motor-change/{motorChange}/approve', [MotorChangeApprovalController::class, 'approve'])->name('motor-change.approve');
            Route::post('motor-change/{motorChange}/reject', [MotorChangeApprovalController::class, 'reject'])->name('motor-change.reject');

            // Certificate routes
            Route::get('/certificates/mtop', function () {
                return view('admin.certificates.MTOP');
            })->name('admin.certificates.mtop');
            Route::get('/certificates/application', function () {
                return view('admin.certificates.application');
            })->name('admin.certificates.application');
            Route::get('/certificates/mayors-permit', function () {
                return view('admin.certificates.mayors_permit');
            })->name('admin.certificates.mayors-permit');

            // Certificate generation routes
            Route::get('/certificates/mtop/{motorDetail}/generate', [\App\Http\Controllers\Admin\CertificateController::class, 'generateMTOP'])->name('certificates.mtop.generate');
            Route::get('/certificates/mtop/{motorDetail}/preview', [\App\Http\Controllers\Admin\CertificateController::class, 'previewMTOP'])->name('certificates.mtop.preview');
            Route::get('/certificates/mayors-permit/{motorDetail}/generate', [\App\Http\Controllers\Admin\CertificateController::class, 'generateMayorsPermit'])->name('certificates.mayors-permit.generate');
            Route::get('/certificates/mayors-permit/{motorDetail}/preview', [\App\Http\Controllers\Admin\CertificateController::class, 'previewMayorsPermit'])->name('certificates.mayors-permit.preview');
            Route::get('/certificates/application/{motorDetail}/generate', [\App\Http\Controllers\Admin\CertificateController::class, 'generateApplication'])->name('certificates.application.generate');
            Route::get('/certificates/application/{motorDetail}/preview', [\App\Http\Controllers\Admin\CertificateController::class, 'previewApplication'])->name('certificates.application.preview');
            Route::get('/certificates/all/{motorDetail}/generate', [\App\Http\Controllers\Admin\CertificateController::class, 'generateAllCertificates'])->name('certificates.all.generate');

            // Payments routes
            // List all payments
            Route::get('/payments', [PaymentController::class, 'index'])
                ->name('payments.index');

            // Mark a payment as paid
            Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])
                ->name('payments.markPaid');

            // Show a specific payment receipt
            Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
                ->name('payments.receipt');
            Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        });

    Route::middleware([RoleMiddleware::class . ':superadmin'])
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/home', [SuperAdminDashboard::class, 'index'])->name('home');
            Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

            // Security Settings Routes (must come before resource routes)
            Route::get('/users/security-settings', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'securitySettings'])->name('users.security-settings');
            Route::put('/users/security-settings', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'updateSecuritySettings'])->name('users.update-security-settings');


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
