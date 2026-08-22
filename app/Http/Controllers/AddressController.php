<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function saveAddress(Request $request)
    {
        try {
            // Validate required fields
            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address_line1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|in:Punjab,Sindh,Khyber Pakhtunkhwa,Balochistan',
                'zip_code' => 'required|string|max:20',
                'country' => 'required|string|in:Pakistan',
                'address_type' => 'required|string|max:255'
            ]);

            $user_id = Auth::id();
            
            $addressData = [
                'user_id' => $user_id,
                'address_type' => $request->address_type,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2 ?? '', // Use empty string if null
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'is_default' => $request->default_address ? 1 : 0,
                'updated_at' => now(),
            ];

            // If it's a new address
            if (empty($request->address_id)) {
                $addressData['created_at'] = now();
                
                // If setting as default, remove default from other addresses
                if ($request->default_address) {
                    DB::table('customer_addresses')
                        ->where('user_id', $user_id)
                        ->update(['is_default' => 0]);
                }
                
                $addressId = DB::table('customer_addresses')->insertGetId($addressData);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Address added successfully',
                    'address_id' => $addressId
                ]);
            } 
            // If updating existing address
            else {
                // If setting as default, remove default from other addresses
                if ($request->default_address) {
                    DB::table('customer_addresses')
                        ->where('user_id', $user_id)
                        ->where('id', '!=', $request->address_id)
                        ->update(['is_default' => 0]);
                }
                
                $affected = DB::table('customer_addresses')
                    ->where('id', $request->address_id)
                    ->where('user_id', $user_id)
                    ->update($addressData);
                
                if ($affected) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Address updated successfully',
                        'address_id' => $request->address_id
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Address not found or no changes made'
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error saving address')
            ], 500);
        }
    }

    public function getAddress($id)
    {
        try {
            $user_id = Auth::id();
            
            $address = DB::table('customer_addresses')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->first();
            
            if ($address) {
                return response()->json([
                    'success' => true,
                    'address' => $address
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching address'
            ], 500);
        }
    }

    public function setDefault($id)
    {
        try {
            $user_id = Auth::id();
            
            // First, set all addresses to not default
            DB::table('customer_addresses')
                ->where('user_id', $user_id)
                ->update(['is_default' => 0]);
            
            // Then set the selected address as default
            $affected = DB::table('customer_addresses')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->update(['is_default' => 1]);
            
            if ($affected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Default address updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error setting default address'
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user_id = Auth::id();
            
            $deleted = DB::table('customer_addresses')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Address deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting address'
            ], 500);
        }
    }
}