<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use Livewire\Component;

class CategoryProducts extends Component
{
  // instanciar las propiedades que quiero comunicar desde el componente livewire
  public $category;

  public $products = null;

  public $pos_color = 0;

  public $product, $isLoading = true;

  public function loadProducts()
  {
    if ($this->category == null) {
      $this->category = Category::all()->random();
    }

    $colorAttrId = Attribute::where('name', 'Color')->value('id');

    $products = $this->category->products()
      ->where('products.status', Product::PUBLICADO)
      ->with([
        'subcategory', 
        'images', 
        'variants' => function($q) {
            $q->where('status', true);
        },
        'variants.attributeOptions' => function($q) use ($colorAttrId) {
            if ($colorAttrId) $q->where('attribute_id', $colorAttrId);
        }
      ])
      ->orderBy('subcategories.position')
      ->orderBy('position')
      ->get();

    if (isset($this->product)) {
      $product_id = $this->product->id;
      $products = $products->filter(function ($product) use ($product_id) {
        return $product->id !== $product_id;
      });
    }

    // Pre-procesar datos para evitar queries en la vista
    $this->products = $products->map(function($product) use ($colorAttrId) {
        // Colores para el preview
        $product->card_colors = collect();
        if ($colorAttrId) {
            $product->card_colors = $product->variants
                ->flatMap(fn($v) => $v->attributeOptions)
                ->where('attribute_id', $colorAttrId)
                ->unique('id');
        }

        // Precios mínimos
        if ($product->variants->count() > 0) {
            $minPrice = $product->variants->min('price');
            $minOffer = $product->variants->where('offer_price', '>', 0)->min('offer_price');
            $product->card_min_price = ($minOffer && $minOffer < $minPrice) ? $minOffer : $minPrice;
            $product->card_has_variants = true;
        } else {
            $product->card_min_price = $product->offer_price ?: $product->price;
            $product->card_has_variants = false;
        }

        return $product;
    });

    $this->emit('glider', $this->category->id); // se emite el evento que se llama como clase en en el componente
    $this->isLoading = false;
  }

  public function render()
  {
    return view('livewire.category-products');
  }
}
