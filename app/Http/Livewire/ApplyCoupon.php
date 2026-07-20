<?php

namespace App\Http\Livewire;

use App\Services\CouponService;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ApplyCoupon extends Component
{
    public string $coupon_code   = '';
    public string $message       = '';
    public string $message_type  = ''; // 'success' | 'error'
    public float  $discount_amount = 0;
    public bool   $shipping_free   = false;
    public bool   $applied         = false;

    public function apply(CouponService $service)
    {
        if ($this->applied) {
            $this->message      = 'Ya tienes un cupón aplicado. Quítalo para usar otro.';
            $this->message_type = 'error';
            return;
        }

        $this->reset(['message', 'message_type', 'discount_amount', 'shipping_free']);

        if (blank($this->coupon_code)) {
            $this->message      = 'Ingresa un código de cupón.';
            $this->message_type = 'error';
            return;
        }

        $coupon = $service->findValid($this->coupon_code);

        if (! $coupon) {
            $this->message      = 'El código no existe o ha expirado.';
            $this->message_type = 'error';
            return;
        }

        $result = $service->apply($coupon, Cart::content());

        if ($result['error']) {
            $this->message      = $result['error'];
            $this->message_type = 'error';
            return;
        }

        $this->discount_amount = $result['discount'];
        $this->shipping_free   = $result['shipping_free'];
        $this->applied         = true;
        $this->message_type    = 'success';

        if ($result['shipping_free']) {
            $this->message = '¡Cupón aplicado! Envío gratis.';
        } else {
            $this->message = '¡Cupón aplicado! Descuento: S/ ' . number_format($result['discount'], 2);
        }

        $this->emit('couponApplied', [
            'discount_amount' => $this->discount_amount,
            'shipping_free'   => $this->shipping_free,
            'coupon_code'     => strtoupper(trim($this->coupon_code)),
        ]);
    }

    public function remove()
    {
        $this->reset(['coupon_code', 'message', 'message_type', 'discount_amount', 'shipping_free', 'applied']);
        $this->emit('couponRemoved');
    }

    public function render()
    {
        return view('livewire.apply-coupon');
    }
}
