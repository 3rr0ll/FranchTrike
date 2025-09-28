<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.settings', compact('admin'));
    }

    /**
     * Update the settings (site settings, admin profile, or password).
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        // Determine what is being updated
        if ($request->has('profile_update')) {
            // Update admin profile
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
                // Add more fields as needed
            ]);

            $changes = [];
            if ($admin->name !== $validated['name']) {
                $changes['name'] = $validated['name'];
            }
            if ($admin->email !== $validated['email']) {
                $changes['email'] = $validated['email'];
            }
            // Add more fields as needed

            if (empty($changes)) {
                return back()->with('info', 'No changes detected in profile.');
            }

            // Use DB update instead of save()
            DB::table('users')->where('id', $admin->id)->update($changes);

            ActivityLogger::log(
                'admin_profile',
                'updated',
                'Admin profile updated.',
                [
                    'admin_id' => $admin->id,
                    'changes' => $changes
                ]
            );

            return redirect()->route('admin.settings')->with('success', 'Profile updated successfully.');
        }

        if ($request->has('password_update')) {
            // Change admin password
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($validated['current_password'], $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            if (Hash::check($validated['password'], $admin->password)) {
                return back()->with('info', 'New password is the same as the current password.');
            }

            // Use DB update instead of save()
            DB::table('users')->where('id', $admin->id)->update([
                'password' => Hash::make($validated['password'])
            ]);

            ActivityLogger::log(
                'admin_profile',
                'password_changed',
                'Admin password was changed.',
                [
                    'admin_id' => $admin->id,
                ]
            );

            return redirect()->route('admin.settings')->with('success', 'Password updated successfully.');
        }


        // Handle profile photo upload to Cloudinary for admin
        if ($request->hasFile('profile_photo')) {
            try {
                // Initialize Cloudinary (reference from Operator/ProfileController)
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
                // (Assuming $admin->cloudinary_profile_photo_id exists)
                if (!empty($admin->cloudinary_profile_photo_id)) {
                    try {
                        $cloudinary->uploadApi()->destroy(
                            $admin->cloudinary_profile_photo_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        Log::error('Failed to delete old Cloudinary admin profile photo: ' . $e->getMessage());
                    }
                }

                // Upload new photo
                $publicId = 'admin_' . $admin->id . '_profile_' . time();
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('profile_photo')->getRealPath(),
                    [
                        'folder' => 'admin_profiles',
                        'public_id' => $publicId,
                        'overwrite' => true,
                        'resource_type' => 'image'
                    ]
                );

                $newPhotoUrl = $upload['secure_url'] ?? null;
                $newPublicId = $upload['public_id'] ?? null;

                // Only update if the photo actually changed
                if (
                    $admin->profile_photo_path === $newPhotoUrl &&
                    $admin->cloudinary_profile_photo_id === $newPublicId
                ) {
                    return back()->with('info', 'No changes detected in profile photo.');
                }

                // Use DB update instead of save()
                DB::table('users')->where('id', $admin->id)->update([
                    'profile_photo_path' => $newPhotoUrl,
                    'cloudinary_profile_photo_id' => $newPublicId,
                ]);

                // Log the profile photo update activity
                ActivityLogger::log(
                    'admin_profile',
                    'updated',
                    'Admin profile photo updated.',
                    [
                        'admin_id' => $admin->id,
                        'profile_photo_path' => $newPhotoUrl,
                        'cloudinary_profile_photo_id' => $newPublicId,
                    ]
                );

                return redirect()->route('admin.settings')->with('success', 'Profile photo updated successfully.');
            } catch (\Exception $e) {
                Log::error('Cloudinary admin profile photo upload failed: ' . $e->getMessage());
                return back()->withErrors(['profile_photo' => 'Profile photo upload failed. Please try again.']);
            }
        }

        // If no changes were made, do not return a success message
        return redirect()->route('admin.settings')->with('info', 'No changes detected.');
    }
}
