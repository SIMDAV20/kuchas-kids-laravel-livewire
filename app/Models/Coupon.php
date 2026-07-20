<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'expires_at'    => 'datetime',
        'is_percentage' => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'targetable', 'coupon_targets');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'targetable', 'coupon_targets');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }
}
