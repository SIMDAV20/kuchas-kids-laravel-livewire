<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageProduct extends Model
{
    use HasFactory;

    protected $table = 'image_product';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    public function product($model)
    {
        return $this->belongsTo($model::class, 'product_id');
    }
}
