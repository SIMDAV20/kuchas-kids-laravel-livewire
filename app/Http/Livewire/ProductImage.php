<?php

namespace App\Http\Livewire;

use App\Models\ColorProduct;
use App\Models\ColorProductSize;
use App\Models\ProductSize;
use Carbon\Carbon;
use Livewire\Component;

class ProductImage extends Component
{
  public $product, $slug;

  public function render()
  {
    $this->slug = $this->product->getFirstPublicSlug();

    [$base_price, $price] = applyMaxMinPrice($this->product);

    $this->product->base_price =  $base_price;
    $this->product->price =  $price;

    return view('livewire.product-image');
  }
}
