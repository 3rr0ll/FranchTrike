<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoginLog;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class LoginSecurityService
{
    public function logLoginAttempt(Request $request, $email, $status, $message = null, User $user = null)
    {
        // Only log if logging is enabled
        if (!SecuritySetting::isLoginLoggingEnabled()) {
            return null;
        }

        return LoginLog::create([
            'user_id' => $user ? $user->id : null,
            'email' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => $status,
            'details' => $message,
        ]);
    }

    public function handleFailedLogin(Request $request, User $user)
    {
        $user->incrementLoginAttempts();
        
        $maxAttempts = SecuritySetting::getMaxLoginAttempts();
        $lockoutMinutes = SecuritySetting::getLockoutDuration();
        
        if ($user->login_attempts >= $maxAttempts) {
            $user->lockAccount($lockoutMinutes);
            $this->logLoginAttempt($request, $user->email, 'locked', 'Account locked due to too many failed attempts', $user);
            return false;
        }
        
        $this->logLoginAttempt($request, $user->email, 'failed', 'Failed login attempt', $user);
        return true;
    }

    public function handleSuccessfulLogin(Request $request, User $user)
    {
        $user->resetLoginAttempts();

        // Prevent duplicate logs: only log if no success in last 1 minute from this IP
        $recent = \App\Models\LoginLog::where('user_id', $user->id)
            ->where('status', 'success')
            ->where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subMinute())
            ->first();

        if (!$recent) {
            $this->logLoginAttempt($request, $user->email, 'success', 'Successful login', $user);
        }

        return true;
    }

    public function checkAccountLocked(User $user)
    {
        if ($user->isLocked()) {
            $remainingTime = $user->getRemainingLockTime();
            $minutes = ceil($remainingTime / 60);
            return "Account is locked. Please try again in {$minutes} minutes.";
        }
        
        return null;
    }

    public function forceLogout(User $user)
    {
        // Revoke all tokens for the user
        $user->tokens()->delete();
        
        // Log the forced logout
        $this->logLoginAttempt(
            request(),
            $user->email,
            'success',
            'Account logged out by administrator',
            $user
        );
        
        return true;
    }

    public function getLoginStats(User $user, $days = 30)
    {
        $logs = $user->loginLogs()->recent($days);
        
        return [
            'total_attempts' => $logs->count(),
            'successful_logins' => $logs->success()->count(),
            'failed_attempts' => $logs->failed()->count(),
            'lockouts' => $logs->locked()->count(),
            'last_login' => $user->loginLogs()->success()->latest()->first()?->created_at,
            'recent_activity' => $logs->latest()->take(10)->get(),
        ];
    }
} 