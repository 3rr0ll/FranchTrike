<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Cloudinary\Cloudinary;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the operator profile edit page.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('operator.edit', compact('user'));
    }

    /**
     * Update the operator profile using the users table.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8', // minimum 8 chars
                'confirmed',
                Password::defaults(),
            ],
            'profile_photo' => [
                'nullable',
                'image',
                'max:2048', // 2MB
                'mimes:jpeg,png,jpg,gif,webp'
            ],
        ],
        [
            'name.required' => 'The name is required.',
            'name.string'   => 'The name must be a string.',
            'name.max'      => 'The name may not be greater than 255 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min'  => 'The password must be at least 8 characters.',
            'profile_photo.image' => 'The profile photo must be an image.',
            'profile_photo.max' => 'The profile photo size must not exceed 2MB.',
            'profile_photo.mimes' => 'Only jpeg, png, jpg, gif, and webp images are allowed.',
        ]);

        $changes = [];

        // Additional programmatic validation
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            if (!$file->isValid()) {
                return back()->withErrors(['profile_photo' => 'Invalid profile photo file.']);
            }
        }

        // Handle profile photo upload to Cloudinary
        if ($request->hasFile('profile_photo')) {
            try {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('cloudinary.cloud_name'),
                        'api_key' => config('cloudinary.api_key'),
                        'api_secret' => config('cloudinary.api_secret'),
                    ],
                    'url' => [
                        'secure' => true
                    ]
                ]);

                if (!empty($user->cloudinary_profile_photo_id)) {
                    try {
                        $cloudinary->uploadApi()->destroy(
                            $user->cloudinary_profile_photo_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to delete old Cloudinary profile photo: ' . $e->getMessage());
                    }
                }

                $publicId = 'user_' . $user->id . '_profile_' . time();
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('profile_photo')->getRealPath(),
                    [
                        'folder' => 'user_profiles',
                        'public_id' => $publicId,
                        'overwrite' => true,
                        'resource_type' => 'image'
                    ]
                );

                ActivityLogger::log(
                    'user_profile',
                    'updated',
                    'User profile photo updated.',
                    [
                        'user_id' => $user->id,
                        'profile_photo_path' => $upload['secure_url'] ?? null,
                        'cloudinary_profile_photo_id' => $upload['public_id'] ?? null,
                    ]
                );

                $user->profile_photo_path = $upload['secure_url'] ?? null;
                $user->cloudinary_profile_photo_id = $upload['public_id'] ?? null;
                $changes['profile_photo'] = 'updated';
            } catch (\Exception $e) {
                Log::error('Cloudinary profile photo upload failed: ' . $e->getMessage());
                return back()->withErrors(['profile_photo' => 'Profile photo upload failed. Please try again.']);
            }
        }

        // Update password if provided and valid
        if ($request->filled('password')) {
            if ($request->password !== $request->password_confirmation) {
                return back()->withErrors(['password' => 'The password and confirmation do not match.']);
            }
            $user->password = Hash::make($request->password);
            $changes['password'] = 'changed';
            ActivityLogger::log(
                'user_profile',
                'password_changed',
                'User password was changed.',
                [
                    'user_id' => $user->id,
                ]
            );
        }

        // Update name if changed and not empty
        if ($request->filled('name') && $request->name !== $user->name) {
            $oldName = $user->name;
            $user->name = $request->name;
            $changes['name'] = [
                'old' => $oldName,
                'new' => $request->name,
            ];

            ActivityLogger::log(
                'user_profile',
                'updated',
                'User name updated.',
                [
                    'user_id' => $user->id,
                    'old_name' => $oldName,
                    'new_name' => $request->name,
                ]
            );
        }

        // Save changes if any
        if (!empty($changes)) {
            $user->save();
            return back()->with('status', 'Profile updated successfully.');
        }

        return back()->with('status', 'No changes detected.');
    }

}
