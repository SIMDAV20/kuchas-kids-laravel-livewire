<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cost'];

    // Relacion de uno a muchos
    public function districts() {
        return $this->hasMany(District::class);
    }
}
