<?php

namespace App\Services;

use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Collection;

class CouponService
{
    /**
     * Validate code and return coupon or null.
     */
    public function findValid(string $code): ?Coupon
    {
        return Coupon::active()->where('code', strtoupper(trim($code)))->first();
    }

    /**
     * Apply coupon to cart items.
     *
     * @return array{discount: float, shipping_free: bool, error: string|null}
     */
    public function apply(Coupon $coupon, Collection $cartItems): array
    {
        $subtotal = (float) Cart::subtotal();

        if ($subtotal < $coupon->min_purchase_amount && $coupon->type !== 'shipping') {
            $missing = number_format($coupon->min_purchase_amount - $subtotal, 2);
            return ['discount' => 0, 'shipping_free' => false, 'error' => "Necesitas S/ {$missing} más para usar este cupón."];
        }

        return match ($coupon->type) {
            'category'      => $this->applyToCategory($coupon, $cartItems),
            'product_group' => $this->applyToProductGroup($coupon, $cartItems),
            'shipping'      => $this->applyShipping($coupon, $subtotal),
        };
    }

    private function applyToCategory(Coupon $coupon, Collection $cartItems): array
    {
        $categoryIds = $coupon->categories->pluck('id');

        $eligible = $cartItems->filter(function ($item) use ($categoryIds) {
            $product = \App\Models\Product::with('subcategory')->find($item->id);
            return $product && $categoryIds->contains($product->subcategory->category_id ?? null);
        });

        if ($eligible->isEmpty()) {
            return ['discount' => 0, 'shipping_free' => false, 'error' => 'Ningún producto del carrito aplica para este cupón.'];
        }

        $discount = $this->calculateDiscount($coupon, $eligible);

        return ['discount' => $discount, 'shipping_free' => false, 'error' => null];
    }

    private function applyToProductGroup(Coupon $coupon, Collection $cartItems): array
    {
        $productIds = $coupon->products->pluck('id');

        $eligible = $cartItems->filter(fn($item) => $productIds->contains($item->id));

        if ($eligible->isEmpty()) {
            return ['discount' => 0, 'shipping_free' => false, 'error' => 'Ningún producto del carrito aplica para este cupón.'];
        }

        $discount = $this->calculateDiscount($coupon, $eligible);

        return ['discount' => $discount, 'shipping_free' => false, 'error' => null];
    }

    private function applyShipping(Coupon $coupon, float $subtotal): array
    {
        if ($coupon->min_purchase_amount > 0 && $subtotal < $coupon->min_purchase_amount) {
            $missing = number_format($coupon->min_purchase_amount - $subtotal, 2);
            return ['discount' => 0, 'shipping_free' => false, 'error' => "Faltan S/ {$missing} para obtener delivery gratis."];
        }

        return ['discount' => 0, 'shipping_free' => true, 'error' => null];
    }

    private function calculateDiscount(Coupon $coupon, Collection $eligible): float
    {
        $base = $eligible->sum(fn($item) => $item->price * $item->qty);

        if ($coupon->is_percentage) {
            return round($base * ($coupon->value / 100), 2);
        }

        return min(round($coupon->value, 2), $base);
    }
}
