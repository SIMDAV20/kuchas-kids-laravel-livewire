<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItem extends Component
{

  public $product, $variant, $quantity, $qty = 1, $options = [];

  public function decrement()
  {
    $this->qty = $this->qty - 1;
  }

  public function increment()
  {
    $this->qty = $this->qty + 1;
  }

  public function getAttribute($attr)
  {
    return $this->product->type_variant == Product::VARBASE ?
      $this->product->$attr :
      $this->variant->$attr;
  }

  public function makePrices($item)
  {
    if (!is_null($item->offer_price)) {
      $this->options['base_price'] = $item->price;
      $price = $item->offer_price;
    } else {
      $price = $item->price;
    }
    return $price;
  }

  public function addItem()
  {
    // Mandatory witch variant is
    if ($this->product->type_variant == Product::VARBASE) {
      $name = $this->product->name;
      $price = $this->makePrices($this->product);
    } else {
      $name = str_replace('-', ' ', $this->variant->slug);
      $price = $this->makePrices($this->variant);
    }

    $this->options['type_variant'] = $this->product->type_variant;

    $data = [
      'id'          => $this->getAttribute('id'),
      'name'        => $name,
      'qty'         => $this->qty,
      'price'       => $price,
      'weight'      => 550,
      'options'     => $this->options
    ];

    Cart::add($data);

    // actualiazr el stock
    $this->quantity = qty_available($this->getAttribute('id'), $this->product->type_variant);

    // refrescar el qty a t1
    $this->reset('qty');

    // Hago un evento para comunicar entre componentes
    $this->emitTo('dropdown-cart', 'render');
    $this->emitTo('cart-mobil', 'render');
  }

  public function mount()
  {
    $this->quantity = qty_available($this->getAttribute('id'), $this->product->type_variant);
    $this->options['image'] = Storage::url(json_decode(@$this->getAttribute('gallery'))[0]);
  }

  public function render()
  {
    return view('livewire.add-cart-item');
  }
}
