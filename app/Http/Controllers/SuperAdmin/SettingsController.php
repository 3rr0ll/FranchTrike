<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display the settings page for SuperAdmin.
     */
    public function index()
    {
        $superadmin = Auth::user();
        return view('superadmin.settings', compact('superadmin'));
    }

    /**
     * Update the settings (profile, password, or profile photo) for SuperAdmin.
     */
    public function update(Request $request)
    {
        $superadmin = Auth::user();

        // Update SuperAdmin profile
        if ($request->has('profile_update')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $superadmin->id,
                // Add more fields as needed
            ]);

            $changes = [];
            if ($superadmin->name !== $validated['name']) {
                $changes['name'] = $validated['name'];
            }
            if ($superadmin->email !== $validated['email']) {
                $changes['email'] = $validated['email'];
            }
            // Add more fields as needed

            if (empty($changes)) {
                return back()->with('info', 'No changes detected in profile.');
            }

            foreach ($changes as $key => $value) {
                $superadmin->$key = $value;
            }
            $superadmin->save();

            \App\Helpers\ActivityLogger::log(
                'superadmin_profile',
                'updated',
                'SuperAdmin profile updated.',
                [
                    'superadmin_id' => $superadmin->id,
                    'changes' => $changes
                ]
            );

            return redirect()->route('superadmin.settings')->with('success', 'Profile updated successfully.');
        }

        // Change SuperAdmin password
        if ($request->has('password_update')) {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($validated['current_password'], $superadmin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            if (Hash::check($validated['password'], $superadmin->password)) {
                return back()->with('info', 'New password is the same as the current password.');
            }

            $superadmin->password = Hash::make($validated['password']);
            $superadmin->save();

            \App\Helpers\ActivityLogger::log(
                'superadmin_profile',
                'password_changed',
                'SuperAdmin password was changed.',
                [
                    'superadmin_id' => $superadmin->id,
                ]
            );

            return redirect()->route('superadmin.settings')->with('success', 'Password updated successfully.');
        }

        // Handle profile photo upload to Cloudinary for SuperAdmin
        if ($request->hasFile('profile_photo')) {
            try {
                $cloudinary = new \Cloudinary\Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('cloudinary.cloud_name'),
                        'api_key' => config('cloudinary.api_key'),
                        'api_secret' => config('cloudinary.api_secret'),
                    ],
                    'url' => [
                        'secure' => true
                    ]
                ]);

                // Optionally, delete old photo from Cloudinary if you store public_id
                if (!empty($superadmin->cloudinary_profile_photo_id)) {
                    try {
                        $cloudinary->uploadApi()->destroy(
                            $superadmin->cloudinary_profile_photo_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Exception $e) {
                        \Log::error('Failed to delete old Cloudinary superadmin profile photo: ' . $e->getMessage());
                    }
                }

                // Upload new photo
                $publicId = 'superadmin_' . $superadmin->id . '_profile_' . time();
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('profile_photo')->getRealPath(),
                    [
                        'folder' => 'superadmin_profiles',
                        'public_id' => $publicId,
                        'overwrite' => true,
                        'resource_type' => 'image'
                    ]
                );

                $newPhotoUrl = $upload['secure_url'] ?? null;
                $newPublicId = $upload['public_id'] ?? null;

                // Only update if the photo actually changed
                if (
                    $superadmin->profile_photo_path === $newPhotoUrl &&
                    $superadmin->cloudinary_profile_photo_id === $newPublicId
                ) {
                    return back()->with('info', 'No changes detected in profile photo.');
                }

                $superadmin->profile_photo_path = $newPhotoUrl;
                $superadmin->cloudinary_profile_photo_id = $newPublicId;
                $superadmin->save();

                \App\Helpers\ActivityLogger::log(
                    'superadmin_profile',
                    'updated',
                    'Superadmin profile photo updated.',
                    [
                        'superadmin_id' => $superadmin->id,
                        'profile_photo_path' => $superadmin->profile_photo_path,
                        'cloudinary_profile_photo_id' => $superadmin->cloudinary_profile_photo_id,
                    ]
                );

                return redirect()->route('superadmin.settings')->with('success', 'Profile photo updated successfully.');

            } catch (\Exception $e) {
                \Log::error('Cloudinary superadmin profile photo upload failed: ' . $e->getMessage());
                return back()->withErrors(['profile_photo' => 'Profile photo upload failed. Please try again.']);
            }
        }

        // If no changes were made, do not return a success message
        return redirect()->route('superadmin.settings')->with('info', 'No changes detected.');
    }
}
