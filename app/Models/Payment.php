<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const APROBADO  = 1;
    const PENDIENTE = 2;
    const RECHAZADO = 3;
    const ANULADO   = 4;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    // Relacion uno a muchos polimórfica
    public function images() { // adjuntar la foto de yape
        return $this->morphOne(Image::class, "imageable");
    }
}
