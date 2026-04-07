<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku', 'price', 'offer_price', 'stock', 'status', 'images'];

    protected $casts = [
        'images' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeOptions()
    {
        return $this->belongsToMany(AttributeOption::class, 'variant_attribute_option', 'variant_id', 'attribute_option_id');
    }

    public function flashOffer()
    {
        return $this->morphOne(FlashOffer::class, 'offerable');
    }
}
