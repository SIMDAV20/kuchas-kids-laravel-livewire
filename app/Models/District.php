<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public $timestamps = false; // deshabilita el updated_at y created_at

    protected $fillable = ['name', 'province_id', 'department_id', 'zone_id'];

    protected $keyType = 'string';

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relacion de uno a muchos inversa
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
