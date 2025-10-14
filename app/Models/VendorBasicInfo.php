<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBasicInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'profile_picture', 'business_name', 'contact_info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}