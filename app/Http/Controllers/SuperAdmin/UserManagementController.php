<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\LoginLog;
use App\Models\SecuritySetting;
use App\Services\LoginSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index()
    {
        $users = User::with('role')->latest()->get();
        $roles = Role::all();
        
        return view('superadmin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('superadmin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);
        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin created new user',
            'User "' . $user->name . '" with email "' . $user->email . '" created.',
            ['user_id' => $user->id]
        );

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $securityService = app(LoginSecurityService::class);
        $stats = $securityService->getLoginStats($user);
        $logs = $user->loginLogs()->latest()->take(10)->get();
        
        return view('superadmin.users.show', compact('user', 'stats', 'logs'));
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($validated);

        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin updated user',
            'User "' . $user->name . '" (ID: ' . $user->id . ') was updated.',
            ['user_id' => $user->id]
        );

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Show password reset form
     */
    public function showPasswordReset(User $user)
    {
        return view('superadmin.users.password-reset', compact('user'));
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin reset user password',
            'Password for user "' . $user->name . '" (ID: ' . $user->id . ') was reset.',
            ['user_id' => $user->id]
        );

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Password reset successfully!');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active,
        ]);

        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin toggled user status',
            'User "' . $user->name . '" (ID: ' . $user->id . ') status was toggled to ' . ($user->is_active ? 'active' : 'inactive') . '.',
            ['user_id' => $user->id]
        );

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('superadmin.users.index')
            ->with('success', "User {$status} successfully!");
    }

    /**
     * Reset login attempts for a user
     */
    public function resetLoginAttempts(User $user)
    {
        $user->resetLoginAttempts();

        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin reset login attempts',
            'Login attempts for user "' . $user->name . '" (ID: ' . $user->id . ') were reset.',
            ['user_id' => $user->id]
        );
        return redirect()->route('superadmin.users.index')
            ->with('success', 'Login attempts reset successfully!');
    }

    /**
     * Lock user account
     */
    public function lockAccount(User $user)
    {
        $user->lockAccount(30); // Lock for 30 minutes

        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin locked user account',
            'User "' . $user->name . '" (ID: ' . $user->id . ') account was locked for 30 minutes.',
            ['user_id' => $user->id]
        );
        return redirect()->route('superadmin.users.index')
            ->with('success', 'User account locked successfully!');
    }

    /**
     * Unlock user account
     */
    public function unlockAccount(User $user)
    {
        $user->unlockAccount();
        return redirect()->route('superadmin.users.index')
            ->with('success', 'User account unlocked successfully!');
    }

    /**
     * Force logout user
     */
    public function forceLogout(User $user)
    {
        $securityService = app(LoginSecurityService::class);
        $securityService->forceLogout($user);
        
        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin forced logout',
            'User "' . $user->name . '" (ID: ' . $user->id . ') was forcibly logged out.',
            ['user_id' => $user->id]
        );

        
        return redirect()->route('superadmin.users.index')
            ->with('success', 'User logged out successfully!');
    }

    /**
     * View user login history
     */
    public function loginHistory(User $user)
    {
        $securityService = app(LoginSecurityService::class);
        $stats = $securityService->getLoginStats($user);
        $logs = $user->loginLogs()->latest()->paginate(20);
        
        return view('superadmin.users.login-history', compact('user', 'stats', 'logs'));
    }

    /**
     * View all login logs
     */
    public function allLoginLogs(Request $request)
    {
        $query = LoginLog::with('user');
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->latest()->paginate(50);
        
        // Get filtered stats
        $filteredQuery = LoginLog::query();
        if ($request->filled('status')) {
            $filteredQuery->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $filteredQuery->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $filteredQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $filteredQuery->whereDate('created_at', '<=', $request->date_to);
        }
        
        $stats = [
            'total_logs' => $filteredQuery->count(),
            'successful_logins' => (clone $filteredQuery)->where('status', 'success')->count(),
            'failed_attempts' => (clone $filteredQuery)->where('status', 'failed')->count(),
            'lockouts' => (clone $filteredQuery)->where('status', 'locked')->count(),
        ];
        
        return view('superadmin.users.login-logs', compact('logs', 'stats'));
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deleting superadmin users
        if ($user->role->name === 'superadmin') {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Cannot delete superadmin users!');
        }

        $user->delete();

        \App\Helpers\ActivityLogger::log(
            'user',
            'Super Admin deleted user',
            'User "' . $user->name . '" with email "' . $user->email . '" was deleted.',
            ['user_id' => $user->id]
        );

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'admins' => User::whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->count(),
            'operators' => User::whereHas('role', function($query) {
                $query->where('name', 'operator');
            })->count(),
            'superadmins' => User::whereHas('role', function($query) {
                $query->where('name', 'superadmin');
            })->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
        ];

        return view('superadmin.users.statistics', compact('stats'));
    }

    /**
     * Show security settings
     */
    public function securitySettings()
    {
        // Add debugging
        Log::info('SecuritySettings method called');
        
        try {
            $settings = SecuritySetting::getAllSettings();
            Log::info('Settings retrieved successfully', ['settings' => $settings]);
            return view('superadmin.users.security-settings', compact('settings'));
        } catch (\Exception $e) {
            Log::error('Error in securitySettings method', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error loading security settings: ' . $e->getMessage());
        }
    }

    /**
     * Update security settings
     */
    public function updateSecuritySettings(Request $request)
    {
        $validated = $request->validate([
            'max_login_attempts' => 'required|integer|min:1|max:20',
            'lockout_duration_minutes' => 'required|integer|min:1|max:1440',
            'enable_login_logging' => 'boolean',
            'enable_account_lockout' => 'boolean',
            'session_timeout_minutes' => 'required|integer|min:1|max:1440',
        ]);

        SecuritySetting::setValue('max_login_attempts', $validated['max_login_attempts'], 'integer');
        SecuritySetting::setValue('lockout_duration_minutes', $validated['lockout_duration_minutes'], 'integer');
        SecuritySetting::setValue('enable_login_logging', $validated['enable_login_logging'] ?? false, 'boolean');
        SecuritySetting::setValue('enable_account_lockout', $validated['enable_account_lockout'] ?? false, 'boolean');
        SecuritySetting::setValue('session_timeout_minutes', $validated['session_timeout_minutes'], 'integer');


        \App\Helpers\ActivityLogger::log(
            'security_settings',
            'Super Admin updated security settings',
            'Security settings were updated.',
            [
                'max_login_attempts' => $validated['max_login_attempts'],
                'lockout_duration_minutes' => $validated['lockout_duration_minutes'],
                'enable_login_logging' => $validated['enable_login_logging'] ?? false,
                'enable_account_lockout' => $validated['enable_account_lockout'] ?? false,
                'session_timeout_minutes' => $validated['session_timeout_minutes'],
                'updated_by' => auth()->id(),
            ]
        );

        return redirect()->route('superadmin.users.security-settings')
            ->with('success', 'Security settings updated successfully!');
    }
} 