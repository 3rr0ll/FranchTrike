<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Cloudinary\Cloudinary;

class ProfileController extends Controller
{
    /**
     * Show the operator profile edit page.
     */
    public function edit()
    {
        $operator = auth()->user(); // authenticated operator
        return view('operator.edit', compact('operator'));
    }

    /**
     * Update the operator profile.
     */
    public function update(Request $request)
    {
        $operator = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'profile_photo' => 'nullable|image|max:2048', // max 2MB
        ]);

        
        $changes = false;

        // Handle profile photo upload to Cloudinary
        if ($request->hasFile('profile_photo')) {
            try {
                // Initialize Cloudinary (reference from DocumentSubmissionController)
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

                // Optionally, delete old photo from Cloudinary if you store public_id
                // (Assuming $operator->cloudinary_profile_photo_id exists)
                if (!empty($operator->cloudinary_profile_photo_id)) {
                    try {
                        $cloudinary->uploadApi()->destroy(
                            $operator->cloudinary_profile_photo_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Log::error('Failed to delete old Cloudinary profile photo: ' . $e->getMessage());
                    }
                }

                // Upload new photo
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

                $operator->profile_photo_path = $upload['secure_url'] ?? null;
                $operator->cloudinary_profile_photo_id = $upload['public_id'] ?? null;

            } catch (\Exception $e) {
                \Log::error('Cloudinary profile photo upload failed: ' . $e->getMessage());
                return back()->withErrors(['profile_photo' => 'Profile photo upload failed. Please try again.']);
            }
        }

        // Update password if provided
        if ($request->filled('password')) {
            $operator->password = Hash::make($request->password);
        }

        // Update name
        $operator->name = $request->name;

        $operator->save();

        if ($changes) {
            $operator->save();
            return back()->with('status', 'Profile updated successfully.');
        }

        return back()->with('status', 'No changes detected.');
    }
    
}
