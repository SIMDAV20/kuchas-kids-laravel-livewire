<?php

namespace App\Http\Livewire;

use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Livewire\Component;

class ProductView extends Component
{
  public $product, $images = [], $qs_color, $qs_size;

  public $select_color_id = null, $select_size_id = null, $sizes = [], $colors = [];

  protected $queryString = [
    'qs_color' => ['as' =>  'c'],
    'qs_size' =>  ['as' =>  't'],

    // 'search' => ['except' => '', 'as' => 's'],
  ];

  public function handleSelectColor(Color $color)
  {
    $this->select_color_id = $color->id;
    $this->qs_color = $color->slug;
    $prev = collect(json_decode($this->product->color_product()->where('color_id', $color->id)->first()->gallery));
    if (count($prev) > 0) $this->images = $prev;
  }

  public function handleSelectSize(Size $size)
  {
    $this->select_size_id = $size->id;
    $this->qs_size = $size->slug;
    $prev = collect(json_decode(@$this->product->color_product()->where('color_id', $size->id)->first()->gallery));
    if (count($prev) > 0) $this->images = $prev;
  }

  public function mount(Request $request)
  {
    $this->images = collect(json_decode($this->product->gallery));

    if (count($this->images) == 0) {
      switch ($this->product->type_variant) {
        case 'colors':
          $item = $this->product->color_product()->where('status', 2)->first();
          $this->images = collect(json_decode($item->gallery));
          break;
        case 'sizes':
          $item = $this->product->product_size()->where('status', 2)->first();
          $this->images = collect(json_decode($item->gallery));
          break;
      }
    }

    switch ($this->product->type_variant) {
      case 'base':
        # code...
        break;
      case 'colors':
        $this->colors = Color::whereIn('id', $this->product->color_product()->where('status', 2)->pluck('color_id'))->get();
        if ($this->qs_color) {
          $fcolor = Color::where('slug', $this->qs_color)->first();
          $this->handleSelectColor($fcolor);
        }
        break;
      case 'sizes':
        $this->sizes = Size::whereIn('id', $this->product->product_size()->where('status', 2)->pluck('size_id'))->get();
        if ($this->qs_size) {
          $fsize = Size::where('slug', $this->qs_size)->first();
          $this->handleSelectSize($fsize);
        }
        break;

        // $this->sizes = Size::whereIn('id', $this->product->product_size()->where('status', 2)->pluck('size_id'))->get();
        // $this->colors = Size::whereIn('id', $this->product->color_product()->where('status', 2)->pluck('color_id'))->get();
    }
  }

  public function render()
  {
    return view('livewire.product-view');
  }
}
