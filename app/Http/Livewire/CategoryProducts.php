<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\ColorProduct;
use App\Models\ColorProductSize;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class CategoryProducts extends Component
{
  // instanciar las propiedades que quiero comunicar desde el componente livewire
  public $category;

  public $products = [];

  public $pos_color = 0;

  public $product, $isLoading = true;

  public function loadProducts()
  {
    if ($this->category == null) {
      $this->category = Category::all()->random();
    }
    // jalar todos los productos por categoria y recien enviarlo al componente category-products
    $this->products = $this->category->products()->where('status', 2)
      ->orderBy('id', 'ASC')->get();

    // $productsQuery = Product::query()->whereHas('subcategory.category', function (Builder $query) {
    //   $query->where('id', $this->category->id);
    // });

    // $this->products = $productsQuery->with(['color_product', 'product_size', 'color_product_size'])
    //   ->whereHas('color_product', function ($query) {
    //     $query->where('status', ColorProduct::PUBLICADO);
    //   })->whereHas('product_size', function ($query) {
    //     $query->where('status', ProductSize::PUBLICADO);
    //   })->whereHas('color_product_size', function ($query) {
    //     $query->where('status', ColorProductSize::PUBLICADO);
    //   })->where('status', Product::PUBLICADO)
    //   ->get();

    // if (isset($this->product)) {
    //   $product_id = $this->product->id;
    //   $this->products = $this->products->filter(function ($product) use ($product_id) {
    //     if ($product->id !== $product_id)
    //       return $product;
    //   });
    // }

    $this->emit('glider', $this->category->id); // se emite el evento que se llama como clase en en el componente
    $this->isLoading = false;
  }

  public function render()
  {
    // $this->products = $this->category->products()->where('status', 2)->take(15)->get();
    return view('livewire.category-products');
  }
}
