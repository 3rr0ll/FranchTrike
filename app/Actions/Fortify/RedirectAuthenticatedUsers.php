<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class RedirectAuthenticatedUsers implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            return redirect('/dashboard');
        }

        switch ($user->role->name) {
            case 'operator':
                return redirect('/operator/home');
            case 'admin':
                return redirect('/admin/home');
            case 'superadmin':
                return redirect('/superadmin/home');
            default:
                return redirect('/dashboard');
        }
    }
}
