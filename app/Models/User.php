<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $incrementing = true;


    protected $fillable = [
        'type',
        'full_name',
        'username',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function vendorProfile()
    {
        return $this->hasOne(VendorBasicInfo::class, 'user_id');
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class, 'user_id');
    }
}
