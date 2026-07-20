<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $fillable = ['id', 'name', 'department_id'];

    protected $keyType = 'string';

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
