<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorProductSize extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $table = "color_product_size";

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
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
