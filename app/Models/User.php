<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'type', 'phone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function vendorProfile()
    {
        return $this->hasOne(VendorBasicInfo::class, 'user_id');
    }

    public function customerProfile()
    {
        return $this->hasOne(CoustomerProfile::class, 'user_id');
    }
}