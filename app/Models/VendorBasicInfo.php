<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBasicInfo extends Model
{
    use HasFactory;
    protected $table = 'vendor_basic_info';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id', 
        'full_name',
        'phone',
        'email',
        'profile_picture', 
        'store_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}