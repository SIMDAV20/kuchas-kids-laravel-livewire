<?php

namespace App\Http\Livewire;

use App\Models\Size;
use Livewire\Component;

class ProductView extends Component
{
  public $product, $images = [], $color, $talla;

  public $select_color_id = null, $select_size_id = null, $sizes = [], $colors = [];

  protected $queryString = ['color', 'talla'];

  public function updatingSelectSizeId()
  {

    dd('aqui');
    $this->images = $this->images->fresh();
  }

  public function mount()
  {
    $this->images = collect(json_decode($this->product->gallery));

    switch ($this->product->type_variant) {
      case 'base':
        # code...
        break;
      case 'colors':
        $this->colors = Size::whereIn('id', $this->product->color_product()->where('status', 2)->pluck('color_id'))->get();
        # code...
        break;
      case 'sizes':
        $this->sizes = Size::whereIn('id', $this->product->product_size()->where('status', 2)->pluck('size_id'))->get();
        break;

        // $this->sizes = Size::whereIn('id', $this->product->product_size()->where('status', 2)->pluck('size_id'))->get();
        // $this->colors = Size::whereIn('id', $this->product->color_product()->where('status', 2)->pluck('color_id'))->get();
        // default:
        //   # code...
        //   break;
    }
  }

  public function render()
  {
    return view('livewire.product-view');
  }
}
