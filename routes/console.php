<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\VendorBasicInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('vendor:create {email} {name=TestVendor}', function ($email, $name) {
    if (User::where('email', $email)->where('type', 'vendor')->exists()) {
        $this->error("Vendor user with email {$email} already exists.");
        return;
    }

    $firstName = strtolower(Str::before($email, '@'));
    $tempUsername = $firstName . rand(1000, 9999);
    while (User::where('username', $tempUsername)->exists()) {
        $tempUsername = $firstName . rand(1000, 9999);
    }

    $user = User::create([
        'type' => 'vendor',
        'full_name' => $name,
        'username' => $tempUsername,
        'email' => $email,
        'phone' => '12345678',
        'password' => Hash::make('password'),
    ]);

    VendorBasicInfo::create([
        'user_id' => $user->user_id,
        'full_name' => $name,
        'store_name' => $name . ' Store',
        'email' => $email,
        'phone' => '12345678',
    ]);

    $this->info("Vendor account for {$name} ({$email}) created successfully!");
    $this->info("Username: {$tempUsername}");
})->purpose('Create a new vendor user and profile in the database');

