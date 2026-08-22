<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'subcategory',
        'quantity',
        'brand',
        'model',
        'pcondition',
        'original_price',
        'delivery_charges',
        'selling_price',
        'mrp',
        'shipping_method',
        'shipping_time',
        'description',
        'location',
        'made_in',
        'return_policy',
        'status',
        'rating',
        'position',
        'views',
        'admin_notes',
        'approved_by',
        'approved_at',
        'video',
        'part_type',
        'fashion_attributes'
    ];

    // fashion_attributes: category-specific fields stored as JSON (currently
    // only Men's Fashion; see storeProduct() in VendorController).
    // TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
    protected $casts = [
        'fashion_attributes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(VendorProductImage::class, 'product_id');
    }

    public function faults()
    {
        return $this->hasMany(VendorProductFault::class, 'product_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}