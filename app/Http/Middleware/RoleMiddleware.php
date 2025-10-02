<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized - User not authenticated');
        }

        if (!$user->role) {
            abort(403, 'Unauthorized - User has no role assigned');
        }

        if (!in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized - User role does not have access to this resource');
        }

        return $next($request);
    }
}
