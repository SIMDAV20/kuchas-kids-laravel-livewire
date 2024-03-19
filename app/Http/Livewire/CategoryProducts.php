<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
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
        $this->products = $this->category->products()
            ->where('status', Product::PUBLICADO)
            ->with('subcategory')
            ->orderBy('subcategories.position')
            ->orderBy('position')
            ->get();

        if (isset($this->product)) {
            $product_id = $this->product->id;
            $this->products = $this->products->filter(function ($product) use ($product_id) {
                if ($product->id !== $product_id)
                    return $product;
            });
        }

        $this->emit('glider', $this->category->id); // se emite el evento que se llama como clase en en el componente
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.category-products');
    }
}
