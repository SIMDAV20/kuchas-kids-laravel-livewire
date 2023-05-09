<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // protected $guarded = ['id', 'created_at', 'updated_at', 'status'];

    const PENDIENTE = 1;
    const RECIBIDO = 2;
    const ENVIADO = 3;
    const ENTREGADO = 4;
    const ANULADO  = 5; // VIGENTE POR 15 MINUTOS

    // Relacion de uno a muchos inversa
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function city()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Relacion uno a muchos polimórfica
    public function images()
    { // adjuntar los recibos
        // return $this->morphMany(Image::class, "imageable");
        return $this->morphOne(Image::class, "imageable");
    }

    // Query Scope
    public function scopeOrderId($query, $id)
    {
        if ($id) {
            return $query->orWhere('id', $id);
        }
    }
    // public function scopeOrderStatus($query, $status) {
    //     $number = 0;
    //     if ($status) {
    //         if (strtolower($status) == 'pendiente') {
    //             $number = 1;
    //         }
    //         if (strtolower($status) == 'recibido') {
    //             $number = 2;
    //         }
    //         return $query->orWhere('status', $status);
    //     }
    // }
    public function scopeShippingCost($query, $shipping_cost)
    {
        if ($shipping_cost) {
            return $query->orWhere('shipping_cost', '>=', "$shipping_cost");
        }
    }
    public function scopeTotal($query, $total)
    {
        if ($total) {
            return $query->orWhere('total', '>=', "$total");
        }
    }
    public function scopeContact($query, $contact)
    {
        if ($contact) {
            return $query->orWhere('contact', 'LIKE', "%$contact%");
        }
    }
    public function scopePhone($query, $phone)
    {
        if ($phone) {
            return $query->orWhere('phone', 'LIKE', "%$phone%");
        }
    }
    public function scopeDocnumber($query, $doc_number)
    {
        if ($doc_number) {
            return $query->orWhere('doc_number', 'LIKE', "%$doc_number%");
        }
    }
    public function scopeOtherContact($query, $contact)
    {
        if ($contact) {
            return $query->orWhere('contact', 'LIKE', "%$contact%");
        }
    }
    public function scopeOtherPhone($query, $phone)
    {
        if ($phone) {
            return $query->orWhere('phone', 'LIKE', "%$phone%");
        }
    }
    public function scopeOtherDocnumber($query, $doc_number)
    {
        if ($doc_number) {
            return $query->orWhere('doc_number', 'LIKE', "%$doc_number%");
        }
    }
    public function scopeExtraNote($query, $note)
    {
        if ($note) {
            return $query->orWhere('extra_note', 'LIKE', "%$note%");
        }
    }
    public function scopePaymentMethod($query, $payment_method)
    {
        if ($payment_method) {
            $number = 0;
            if (strtolower($payment_method) == 'mercadopago') {
                $number = 1;
            }
            if (strtolower($payment_method) == 'yape') {
                $number = 2;
            }
            return $query->orWhere('payment_method', $number);
        }
    }
}
