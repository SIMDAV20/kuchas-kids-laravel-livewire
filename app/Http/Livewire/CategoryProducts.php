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
  public $category, $products = [];

  public $isLoading = true;

  public function loadProducts()
  {
    if ($this->category == null) {
      $this->category = Category::all()->random();
    }
    // jalar todos los productos por categoria y recien enviarlo al componente category-products
    $this->products = $this->category->products()->where('status', Product::PUBLICADO)
      ->orderBy('id', 'ASC')->get();

    $this->emit('glider', $this->category->id); // se emite el evento que se llama como clase en en el componente
    $this->isLoading = false;
  }

  public function render()
  {
    // $this->products = $this->category->products()->where('status', 2)->take(15)->get();
    return view('livewire.category-products');
  }
}
