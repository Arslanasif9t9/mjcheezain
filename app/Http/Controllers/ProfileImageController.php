<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileImageController extends Controller
{
    /**
     * Upload profile image
     */
    public function uploadProfileImage(Request $request)
    {
        // Get authenticated user
        $user_id = Auth::id();
        
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Validate request
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            // Check if file exists
            if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                $file = $request->file('profile_image');
                
                // Generate unique filename
                $fileExtension = $file->getClientOriginalExtension();
                $newFilename = uniqid() . '.' . $fileExtension;
                
                // Store file in public/storage/customer/profile
                $destinationPath = public_path('storage/customer/profile');
                
                // Create directory if it doesn't exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                // Move uploaded file
                $file->move($destinationPath, $newFilename);
                
                // Check if profile exists
                $existingProfile = DB::table('customer_profile')
                                    ->where('user_id', $user_id)
                                    ->first();
                
                // Delete old image if exists
                if ($existingProfile && $existingProfile->profile_image) {
                    $oldImagePath = public_path('storage/customer/profile/' . $existingProfile->profile_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // Update or insert profile image
                if ($existingProfile) {
                    // Update existing profile
                    DB::table('customer_profile')
                        ->where('user_id', $user_id)
                        ->update([
                            'profile_image' => $newFilename,
                            'updated_at' => now()
                        ]);
                } else {
                    // Insert new profile record
                    DB::table('customer_profile')->insert([
                        'user_id' => $user_id,
                        'profile_image' => $newFilename,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Profile image uploaded successfully',
                    'image_url' => asset('storage/customer/profile/' . $newFilename)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No valid image file found'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error')
            ], 500);
        }
    }

    /**
     * Remove profile image
     */
    public function removeProfileImage(Request $request)
    {
        $user_id = Auth::id();
        
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        try {
            // Get current profile
            $existingProfile = DB::table('customer_profile')
                                ->where('user_id', $user_id)
                                ->first();
            
            if ($existingProfile && $existingProfile->profile_image) {
                // Delete image file
                $imagePath = public_path('storage/customer/profile/' . $existingProfile->profile_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                
                // Update database (set to null or default)
                DB::table('customer_profile')
                    ->where('user_id', $user_id)
                    ->update([
                        'profile_image' => 'default_profile.webp',
                        'updated_at' => now()
                    ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Profile image removed successfully',
                    'image_url' => asset('storage/customer/profile/default_profile.webp')
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No profile image found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error')
            ], 500);
        }
    }

    /**
     * Upload banner image
     */
    public function uploadBannerImage(Request $request)
    {
        $user_id = Auth::id();
        
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Validate request
        $request->validate([
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ]);

        try {
            if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
                $file = $request->file('banner_image');
                
                // Generate unique filename
                $fileExtension = $file->getClientOriginalExtension();
                $newFilename = uniqid() . '_banner.' . $fileExtension;
                
                // Store file in public/storage/customer/banner
                $destinationPath = public_path('storage/customer/banner');
                
                // Create directory if it doesn't exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                // Move uploaded file
                $file->move($destinationPath, $newFilename);
                
                // Check if banner exists for this user
                $existingBanner = DB::table('customer_banner')
                                    ->where('user_id', $user_id)
                                    ->first();
                
                // Delete old banner if exists
                if ($existingBanner && $existingBanner->banner_image) {
                    $oldImagePath = public_path('storage/customer/banner/' . $existingBanner->banner_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // Update or insert banner
                if ($existingBanner) {
                    // Update existing banner
                    DB::table('customer_banner')
                        ->where('user_id', $user_id)
                        ->update([
                            'banner_image' => $newFilename,
                            'updated_at' => now()
                        ]);
                } else {
                    // Insert new banner record
                    DB::table('customer_banner')->insert([
                        'user_id' => $user_id,
                        'banner_image' => $newFilename,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Banner image uploaded successfully',
                    'image_url' => asset('storage/customer/banner/' . $newFilename)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No valid banner image found'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error')
            ], 500);
        }
    }

    /**
     * Remove banner image
     */
    public function removeBannerImage(Request $request)
    {
        $user_id = Auth::id();
        
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        try {
            // Get current banner
            $existingBanner = DB::table('customer_banner')
                                ->where('user_id', $user_id)
                                ->first();
            
            if ($existingBanner && $existingBanner->banner_image) {
                // Delete banner file
                $imagePath = public_path('storage/customer/banner/' . $existingBanner->banner_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                
                // Update database (set to null)
                DB::table('customer_banner')
                    ->where('user_id', $user_id)
                    ->update([
                        'banner_image' => null,
                        'updated_at' => now()
                    ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Banner image removed successfully',
                    'image_url' => null
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No banner image found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error')
            ], 500);
        }
    }
}