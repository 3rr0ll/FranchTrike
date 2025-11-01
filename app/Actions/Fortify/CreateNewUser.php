<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {

        $turnstileToken = $input['cf-turnstile-response'] ?? null;
        $verify = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret'),
            'response' => $turnstileToken,
            'remoteip' => request()->ip(),
        ]);
    
        if (! ($verify->json('success') ?? false)) {
            throw ValidationException::withMessages([
                'turnstile' => 'Bot verification failed. Please try again.',
            ]);
        }


        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => 1, 
            'is_active' => true,
        ]);


        return $user;
    }
}