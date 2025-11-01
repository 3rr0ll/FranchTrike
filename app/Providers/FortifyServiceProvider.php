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
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Actions\Fortify\RedirectAuthenticatedUsers;
use App\Services\LoginSecurityService;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use Laravel\Fortify\Http\Responses\VerifyEmailResponse;
use Illuminate\Auth\Events\Verified; 
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->singleton(LoginResponse::class, RedirectAuthenticatedUsers::class);

        // Custom RegisterResponse to redirect to verification notice
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    // Redirect to email verification notice instead of operator/create
                    return redirect()->route('verification.notice');
                }
            };
        });

        $this->app->singleton(VerifyEmailViewResponse::class, function () {
            return new class implements VerifyEmailViewResponse {
                public function toResponse($request)
                {
                    return view('auth.verify-email');
                }
            };
        });
        
        // Custom VerifyEmailResponse to redirect to /operator/create
        $this->app->singleton(VerifyEmailResponse::class, function () {
            return new class implements VerifyEmailResponse {
                public function toResponse($request)
                {
                    // Role is already assigned during registration, no need to set it again
                    return redirect()->intended('/operator/create');
                }
            };
        });

        \Illuminate\Support\Facades\Event::listen(Verified::class, function ($event) {
            $user = $event->user;
            if ($user && !$user->role_id) {
                $user->role_id = 1;
                $user->save();
            }
        });
        Fortify::authenticateUsing(function (Request $request) {

            // Cloudflare Turnstile check
            $turnstile = $request->input('cf-turnstile-response');
            $verify = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret'),
                'response' => $turnstile,
                'remoteip' => $request->ip(),
            ]);
        
            if (! ($verify->json('success') ?? false)) {
                throw ValidationException::withMessages([
                    'turnstile' => 'Bot verification failed. Please try again.',
                ]);
            }

            $user = \App\Models\User::where('email', $request->email)->first();
            $securityService = app(LoginSecurityService::class);

            // Check if user exists and is active
            if (!$user || !$user->is_active) {
                if ($user) {
                    $securityService->logLoginAttempt($request, $request->email, 'fail', 'User account is inactive');
                }
                return null;
            }

            // Check if email is verified (only for non-admin logins)
            if (!$request->has('is_admin_login') && !$user->hasVerifiedEmail()) {
                return null; // This will redirect to verification notice
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
                if (!in_array(optional($user->role)->name, ['admin', 'superadmin'])) {
                    $securityService->logLoginAttempt($request, $request->email, 'fail', 'Unauthorized role for admin login', $user);
                    return null;
                }
            } else {
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
    }
}
