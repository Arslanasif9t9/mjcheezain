<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProductFault extends Model
{
    use HasFactory;
    public $timestamps = false; 

    protected $fillable = [
        'product_id',
        'fault_image',
        'fault_description'
    ];

    public function product()
    {
        return $this->belongsTo(VendorProduct::class, 'product_id');
    }
}