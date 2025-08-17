@extends('layouts.superadmin')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    
        <!-- Security Settings Form -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Login Security Configuration</h3>
                
                <form method="POST" action="{{ route('superadmin.users.update-security-settings') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Login Attempts -->
                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700">
                                Maximum Login Attempts
                            </label>
                            <input type="number" 
                                   name="max_login_attempts" 
                                   id="max_login_attempts" 
                                   value="{{ $settings['max_login_attempts'] ?? 5 }}"
                                   min="1" 
                                   max="20"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                Number of failed attempts before account lockout
                            </p>
                        </div>

                        <!-- Lockout Duration -->
                        <div>
                            <label for="lockout_duration_minutes" class="block text-sm font-medium text-gray-700">
                                Lockout Duration (minutes)
                            </label>
                            <input type="number" 
                                   name="lockout_duration_minutes" 
                                   id="lockout_duration_minutes" 
                                   value="{{ $settings['lockout_duration_minutes'] ?? 30 }}"
                                   min="1" 
                                   max="1440"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                How long accounts remain locked after failed attempts
                            </p>
                        </div>

                        <!-- Session Timeout -->
                        <div>
                            <label for="session_timeout_minutes" class="block text-sm font-medium text-gray-700">
                                Session Timeout (minutes)
                            </label>
                            <input type="number" 
                                   name="session_timeout_minutes" 
                                   id="session_timeout_minutes" 
                                   value="{{ $settings['session_timeout_minutes'] ?? 120 }}"
                                   min="1" 
                                   max="1440"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                How long user sessions remain active
                            </p>
                        </div>

                        <!-- Enable Account Lockout -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="enable_account_lockout" 
                                       id="enable_account_lockout" 
                                       value="1"
                                       {{ ($settings['enable_account_lockout'] ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_account_lockout" class="ml-2 block text-sm text-gray-900">
                                    Enable Account Lockout
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Automatically lock accounts after failed attempts
                            </p>
                        </div>

                        <!-- Enable Login Logging -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="enable_login_logging" 
                                       id="enable_login_logging" 
                                       value="1"
                                       {{ ($settings['enable_login_logging'] ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_login_logging" class="ml-2 block text-sm text-gray-900">
                                    Enable Login Logging
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Log all login attempts for audit purposes
                            </p>
                        </div>
                    </div>

                    <!-- Current Settings Summary -->
                    <div class="mt-8 bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Current Security Status</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Account Lockout:</span>
                                <span class="ml-2 {{ ($settings['enable_account_lockout'] ?? true) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ($settings['enable_account_lockout'] ?? true) ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Login Logging:</span>
                                <span class="ml-2 {{ ($settings['enable_login_logging'] ?? true) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ($settings['enable_login_logging'] ?? true) ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Max Attempts:</span>
                                <span class="ml-2 text-gray-600">{{ $settings['max_login_attempts'] ?? 5 }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Lockout Duration:</span>
                                <span class="ml-2 text-gray-600">{{ $settings['lockout_duration_minutes'] ?? 30 }} minutes</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Session Timeout:</span>
                                <span class="ml-2 text-gray-600">{{ $settings['session_timeout_minutes'] ?? 120 }} minutes</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Security Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Information -->
        <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Security Features</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Account Protection</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Automatic account lockout after failed attempts</li>
                            <li>• Configurable lockout duration</li>
                            <li>• Manual account unlock by administrators</li>
                            <li>• Force logout capability for suspicious activity</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Audit & Monitoring</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Comprehensive login attempt logging</li>
                            <li>• IP address and user agent tracking</li>
                            <li>• Individual user login history</li>
                            <li>• System-wide login activity monitoring</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection