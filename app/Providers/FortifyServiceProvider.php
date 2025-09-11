<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Actions\Fortify\RedirectAuthenticatedUsers;
use App\Services\LoginSecurityService;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Http\RedirectResponse;

class FortifyServiceProvider extends ServiceProvider
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
        $this->app->singleton(LoginResponse::class, RedirectAuthenticatedUsers::class);

        // Custom RegisterResponse to redirect to operator/create after registration
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect()->intended('/operator/create');
                }
            };
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();
            $securityService = app(LoginSecurityService::class);

            // Check if user exists and is active
            if (!$user || !$user->is_active) {
                if ($user) {
                    $securityService->logLoginAttempt($request, $request->email, 'fail', 'User account is inactive');
                }
                return null;
            }

            // Check if account is locked
            $lockMessage = $securityService->checkAccountLocked($user);
            if ($lockMessage) {
                $securityService->logLoginAttempt($request, $request->email, 'locked', $lockMessage, $user);
                return null;
            }

            // Validate credentials
            if (!Hash::check($request->password, $user->password)) {
                $securityService->handleFailedLogin($request, $user);
                return null;
            }

            // Check role-based access
            if ($request->has('is_admin_login')) {
                // Only allow admin or superadmin to login here
                if (!in_array(optional($user->role)->name, ['admin', 'superadmin'])) {
                    $securityService->logLoginAttempt($request, $request->email, 'fail', 'Unauthorized role for admin login', $user);
                    return null;
                }
            } else {
                // Default login (operator only)
                if (optional($user->role)->name !== 'operator') {
                    $securityService->logLoginAttempt($request, $request->email, 'fail', 'Unauthorized role for operator login', $user);
                    return null;
                }
            }

            // Successful login
            $securityService->handleSuccessfulLogin($request, $user);
            return $user;
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Bind custom login redirect
        $this->app->singleton(LoginResponse::class, RedirectAuthenticatedUsers::class);
    }
}
