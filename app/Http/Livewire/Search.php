<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Subcategory;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class Search extends Component
{
  public $search;

  public $open = false;

  public function updatingSearch($value)
  {
    if ($value) {
      $this->open = true;
    } else {
      $this->open = false;
    }
  }

  public function render()
  {
    $products = collect([]);
    if ($this->search) {
      $take = 8;
      $subcategories = collect(Subcategory::name($this->search)->get());
      if (count($subcategories) > 0) {
        $products = $products->merge(
          Product::whereIn('subcategory_id', $subcategories->pluck('id'))
            ->search($this->search)
            ->orderBy('name', 'asc')
            ->take($take)
            ->get()
        );
        $products = $products->unique()->take($take);
      } else {
        $products = Product::search($this->search)
          ->orderBy('name', 'asc')
          ->take($take)
          ->get();
      }
    }

    foreach ($products as $product) {
      switch ($product->type_variant) {
        case Product::VARBASE:
          $product->single_img = json_decode(@$product->gallery)[0] ?? null;
          break;
        case Product::VARCOLORS:
          $p_color_prod = $product->color_product->first();
          $product->single_img = json_decode($p_color_prod->gallery)[0] ?? null;
          break;
        case Product::VARSIZES:
          $p_prod_size = $product->product_size->first();
          $product->single_img = json_decode($p_prod_size->gallery)[0] ?? null;
          break;
          // case Product::VARCOLORSSIZES:
          //     $product->single_img =
          //     break;
        default:
          $product->single_img = null;
          break;
      }

      [$base_price, $price] = applyMaxMinPrice($product);

      $product->base_price =  $base_price;
      $product->price =  $price;
    }

    return view('livewire.search', compact('products'));
  }
}
