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
        $operator = Auth::user()->operator;
        return view('operator.edit', compact('operator'));
    }

    /**
     * Update the operator profile.
     */
    public function update(Request $request)
    {
        $operator = Auth::user()->operator;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Optionally prevent duplicate names within operators table:
                // 'unique:operators,name,' . $operator->id,
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
            // You could add additional custom checks here (e.g., aspect ratio)
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

                if (!empty($operator->cloudinary_profile_photo_id)) {
                    try {
                        $cloudinary->uploadApi()->destroy(
                            $operator->cloudinary_profile_photo_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to delete old Cloudinary profile photo: ' . $e->getMessage());
                    }
                }

                $publicId = 'operator_' . $operator->id . '_profile_' . time();
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('profile_photo')->getRealPath(),
                    [
                        'folder' => 'operator_profiles',
                        'public_id' => $publicId,
                        'overwrite' => true,
                        'resource_type' => 'image'
                    ]
                );

                ActivityLogger::log(
                    'operator_profile',
                    'updated',
                    'Operator profile photo updated.',
                    [
                        'operator_id' => $operator->id,
                        'profile_photo_path' => $upload['secure_url'] ?? null,
                        'cloudinary_profile_photo_id' => $upload['public_id'] ?? null,
                    ]
                );

                $operator->profile_photo_path = $upload['secure_url'] ?? null;
                $operator->cloudinary_profile_photo_id = $upload['public_id'] ?? null;
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
            $operator->password = Hash::make($request->password);
            $changes['password'] = 'changed';
            ActivityLogger::log(
                'operator_profile',
                'password_changed',
                'Operator password was changed.',
                [
                    'operator_id' => $operator->id,
                ]
            );
        }

        // Update name if changed and not empty
        if ($request->filled('name') && $request->name !== $operator->name) {
            $oldName = $operator->name;
            $operator->name = $request->name;
            $changes['name'] = [
                'old' => $oldName,
                'new' => $request->name,
            ];
            ActivityLogger::log(
                'operator_profile',
                'updated',
                'Operator name updated.',
                [
                    'operator_id' => $operator->id,
                    'old_name' => $oldName,
                    'new_name' => $request->name,
                ]
            );
        }

        // Save changes if any
        if (!empty($changes)) {
            $operator->save();
            return back()->with('status', 'Profile updated successfully.');
        }

        return back()->with('status', 'No changes detected.');
    }
    
}
