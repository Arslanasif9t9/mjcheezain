<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProductImage extends Model
{
    use HasFactory;
    public $timestamps = false; 

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary'
    ];

    public function product()
    {
        return $this->belongsTo(VendorProduct::class, 'product_id');
    }
}