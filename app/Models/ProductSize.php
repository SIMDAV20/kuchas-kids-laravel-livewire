<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $table = "product_size";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['images'];

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

    // Relacion uno a muchos polimórfica
    public function images()
    {
        return $this->morphMany(Image::class, "imageable");
    }

    // URL AMIGABLES
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
