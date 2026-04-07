<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashOffer extends Model
{
    use HasFactory;

    protected $fillable = ['offerable_id', 'offerable_type', 'flash_price', 'start_at', 'end_at', 'status'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function offerable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include active flash offers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
                     ->where('start_at', '<=', now())
                     ->where('end_at', '>=', now());
    }
}
