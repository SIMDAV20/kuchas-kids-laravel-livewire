<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItem extends Component
{
    public $product;
    public $variant;
    public $quantity;
    public $qty = 1;
    public $options = [];

    protected $listeners = ['variantChanged'];

    public function mount(Product $product, $variantId = null)
    {
        $this->product = $product;
        if ($variantId) {
            $this->variant = ProductVariant::find($variantId);
        }
        $this->updateState();
    }

    public function variantChanged($variantId)
    {
        $this->variant = ProductVariant::find($variantId);
        $this->updateState();
    }

    public function updateState()
    {
        if ($this->variant) {
            $this->quantity = qty_available($this->product->id, $this->variant->id);
            $variantImages = $this->variant->images ?? [];
            $this->options['image'] = $this->firstImageUrl($variantImages)
                ?? $this->firstImageUrl($this->product->images ?? []);
        } else {
            $this->quantity = qty_available($this->product->id);
            $this->options['image'] = $this->firstImageUrl($this->product->images ?? []);
        }

        if ($this->qty > $this->quantity) {
            $this->qty = $this->quantity > 0 ? 1 : 0;
        }
    }

    private function firstImageUrl(array $ids): ?string
    {
        if (empty($ids)) return null;
        $img = \App\Models\Image::find($ids[0]);
        return $img ? Storage::url($img->url) : null;
    }

    public function decrement()
    {
        $this->qty = $this->qty - 1;
    }

    public function increment()
    {
        $this->qty = $this->qty + 1;
    }

    public function addItem()
    {
        $itemToPrice = $this->variant ?? $this->product;
        [$base_price, $price] = applyOffer($itemToPrice);
        
        $this->options['base_price'] = $base_price;
        
        if ($this->variant) {
            $this->options['variant_id'] = $this->variant->id;
            // Add attribute values to options for display in cart
            foreach ($this->variant->attributeOptions as $option) {
                $this->options[$option->attribute->name] = $option->value;
            }
        }

        Cart::add([
            'id'          => $this->product->id,
            'name'        => $this->product->name,
            'qty'         => $this->qty,
            'price'       => $price,
            'weight'      => 550,
            'options'     => $this->options
        ]);

        $this->updateState();
        $this->reset('qty');

        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }

    public function render()
    {
        return view('livewire.add-cart-item');
    }
}
