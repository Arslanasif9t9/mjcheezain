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
        'free_delivery',
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
        'fashion_attributes',
        'jewelry_attributes',
        'fragrance_attributes',
        'bags_attributes',
        'gym_attributes',
        'kitchen_attributes',
        'smarthome_attributes',
        'personalcare_attributes'
    ];

    // fashion_attributes: category-specific fields stored as JSON, shared by
    // all 5 fashion categories (Men's/Women's/Kids & Baby/Footwear/Fashion
    // Accessories & Bags — see buildFashionAttributes() in VendorController).
    // jewelry_attributes: same pattern for Jewellery & Accessories (own bucket,
    // not reused from fashion_attributes; see buildJewelryAttributes() in VendorController).
    // fragrance_attributes / bags_attributes / gym_attributes / kitchen_attributes /
    // smarthome_attributes / personalcare_attributes: same pattern, one own bucket
    // per category — see buildFragranceAttributes() / buildSharedCategoryAttributes()
    // in VendorController.
    protected $casts = [
        'fashion_attributes' => 'array',
        'jewelry_attributes' => 'array',
        'fragrance_attributes' => 'array',
        'bags_attributes' => 'array',
        'gym_attributes' => 'array',
        'kitchen_attributes' => 'array',
        'smarthome_attributes' => 'array',
        'personalcare_attributes' => 'array',
        'free_delivery' => 'boolean',
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