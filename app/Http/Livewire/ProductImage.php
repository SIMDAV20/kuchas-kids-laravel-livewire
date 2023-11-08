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

    [$min, $max] = $this->product->getMinPrice();
    $this->product->min_price = $min;

    return view('livewire.product-image');
  }
}
