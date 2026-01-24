<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        // Example: Fetch customer orders
        $orders = DB::table('orders')->where('user_id', $user->user_id)->count();

        return view('customer.dashboard', compact([
            'basic_info'
        ]));
    }

    public function profile() {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        $banner = DB::table('customer_banner')
                        ->where('user_id', $user->user_id)
                        ->first();
        $bannerImage = $banner->banner_image ?? "default_img.png";
        // Example: Fetch customer orders
        $orders = DB::table('orders')->where('user_id', $user->user_id)->count();

        return view('customer.profile', compact([
            'basic_info', 'bannerImage'
        ]));
    }

    public function editProfile() {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        // Example: Fetch customer orders
        $orders = DB::table('orders')->where('user_id', $user->user_id)->count();

        return view('customer.edit-profile', compact([
            'basic_info'
        ]));
    }

    public function saveProfile(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'birthday' => 'nullable|date',
            'bio' => 'nullable|string',
            'profile-upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user_id = Auth::id();
        $firstName = $request->input('first-name');
        $lastName = $request->input('last-name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $birthday = $request->input('birthday');
        $bio = $request->input('bio');
        $profileImage = null;
        
        // Handle profile image upload
        if ($request->hasFile('profile-upload') && $request->file('profile-upload')->isValid()) {
            $file = $request->file('profile-upload');
            
            // Generate unique filename
            $fileExtension = $file->getClientOriginalExtension();
            $newFilename = uniqid() . '.' . $fileExtension;
            
            // Store file
            $profileImage = $file->storeAs('customer/profile', $newFilename, 'public');
            $destinationPath = public_path('storage/customer/profile');
            $file->move($destinationPath, $newFilename);
            $profileImage = $newFilename;
        }

        try {
            // Check if profile exists
            $existingProfile = DB::table('customer_profile')
                                ->where('user_id', $user_id)
                                ->first();

            if ($existingProfile) {
                // Update existing profile
                $updateData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'birthday' => $birthday,
                    'bio' => $bio,
                    // 'profile_image' => $profileImage ?? 'default_profile.webp',
                    'updated_at' => now(),
                ];
                // dd($existingProfile);

                if ($profileImage !== null) {
                    $updateData['profile_image'] = $profileImage;
                    
                    // Delete old profile image if exists
                    // if ($existingProfile->profile_image) {
                    //     Storage::disk('public')->delete($existingProfile->profile_image);
                    // }
                }

                $affected = DB::table('customer_profile')
                            ->where('user_id', $user_id)
                            ->update($updateData);

                if ($affected > 0) {
                    return redirect()->route('customer.cprofile')->with('success', 'Profile updated successfully.');
                } else {
                    return redirect()->route('customer.cprofile')->with('info', 'No changes made to the profile.');
                }
            } else {
                // Insert new profile
                $insertData = [
                    'user_id' => $user_id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'birthday' => $birthday,
                    'bio' => $bio,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($profileImage !== null) {
                    $insertData['profile_image'] = $profileImage;
                }

                $inserted = DB::table('customer_profile')->insert($insertData);

                if ($inserted) {
                    return redirect()->route('profile.page')->with('success', 'Profile saved successfully.');
                } else {
                    return redirect()->route('profile.page')->with('error', 'Error saving profile.');
                }
            }

        } catch (\Exception $e) {
            return redirect()->route('customer.cprofile')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function addresses() {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        // Example: Fetch customer orders
        $addresses = DB::table('customer_addresses')->where('user_id', $user->user_id)->get()->toArray();

        return view('customer.addresses', compact([
            'basic_info', 'addresses'
        ]));
    }

    public function wishlist() {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        // Example: Fetch customer orders
        $fav = DB::table('favorites')->where('user_id', $user->user_id)->get()->toArray();

        return view('customer.wishlist', compact([
            'basic_info', 'fav'
        ]));
    }

    public function orders() {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        // Example: Fetch customer orders
        $orders = DB::table('orders')->where('user_id', $user->user_id)->get()->toArray();

        return view('customer.orders', compact([
            'basic_info', 'orders'
        ]));
    }





    public function notifications()
    {
        $user = Auth::user();
        
        // Get basic customer profile info
        $basic_info = DB::table('customer_profile')
            ->where('user_id', $user->user_id)
            ->first();
        
        // Get notifications grouped by date
        $notifications = DB::table('notifications')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($notification) {
                return $this->formatDateGroup($notification->created_at);
            });
        
        return view('customer.notifications', compact('basic_info', 'notifications'));
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $user = Auth::user();
        
        DB::table('notifications')
            // ->where('id', $id)
            ->where('user_id', $user->user_id)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Format date for grouping
     */
    private function formatDateGroup($date)
    {
        $notificationDate = \Carbon\Carbon::parse($date);
        $today = \Carbon\Carbon::today();
        $yesterday = \Carbon\Carbon::yesterday();
        
        if ($notificationDate->isToday()) {
            return 'Today';
        } elseif ($notificationDate->isYesterday()) {
            return 'Yesterday';
        } elseif ($notificationDate->isCurrentWeek()) {
            return 'This Week';
        } else {
            return $notificationDate->format('F Y');
        }
    }
}
