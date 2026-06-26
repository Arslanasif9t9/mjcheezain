<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VendorBasicInfo;
use App\Models\CustomerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed a test Vendor account
        $vendorUser = User::create([
            'type' => 'vendor',
            'full_name' => 'Test Vendor',
            'username' => 'testvendor',
            'email' => 'vendor@example.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
        ]);

        VendorBasicInfo::create([
            'user_id' => $vendorUser->user_id,
            'full_name' => 'Test Vendor',
            'store_name' => 'Test Vendor Store',
            'email' => 'vendor@example.com',
            'phone' => '1234567890',
            'profile_visibility' => true,
        ]);

        // 2. Seed a test Customer account
        $customerUser = User::create([
            'type' => 'customer',
            'full_name' => 'Test Customer',
            'username' => 'testcustomer',
            'email' => 'customer@example.com',
            'phone' => '0987654321',
            'password' => Hash::make('password'),
        ]);

        CustomerProfile::create([
            'user_id' => $customerUser->user_id,
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => '0987654321',
        ]);
    }
}

