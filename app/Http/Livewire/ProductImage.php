<?php

namespace App\Http\Livewire;

use App\Models\Color;
use App\Models\Product;
use Livewire\Component;

class ProductImage extends Component
{
    public $product, $colors = [], $color_selected;

    public function render()
    {
        $slug = $this->product->getFirstPublicSlug();

        [$base_price, $price] = applyMaxMinPrice($this->product);

        // $var_product = currentVarProduct($this->product->type_variant);

        $imagePath = $this->product->getFirstImageURL();

        if (in_array($this->product->type_variant, [Product::VARCOLORS, Product::VARCOLORSSIZES])) {
            $colors_products = $this->product->color_product()->where('status', Product::PUBLICADO);
            $this->colors = Color::whereIn('id', $colors_products->get()->pluck('color_id')->values())->get();
            $color_selected = $this->colors->first();

            // dd($color_selected);

            // $imagePath = $colors_products->where('color_id', $color_selected)->first();
            // dd($imagePath);
        }

        return view('livewire.product-image', compact('base_price', 'price', 'slug', 'var_product', 'imagePath'));
    }
}
