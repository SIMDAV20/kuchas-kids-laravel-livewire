<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ProductSize extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $table = "product_size";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // protected $fillabe = ['id','size_id', 'quantity', 'price', 'offer_price'];

    // Relacion uno a muchos inversa
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ImageProduct::class, 'product_id')->with('image');
    }

    public function image_product(): HasManyThrough
    {
        return $this->hasManyThrough(Image::class, ImageProduct::class, 'product_id', 'id', 'id', 'image_id');
    }

    public function getFirstImageURL()
    {
        $image_prod = $this->images()->first();
        return $image_prod ? $image_prod->image->url : null;
    }

    // URL AMIGABLES
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
