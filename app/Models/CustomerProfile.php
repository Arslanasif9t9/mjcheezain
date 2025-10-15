<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory;
    protected $table = 'customer_profile';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'profile_image',
        'shipping_address',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}