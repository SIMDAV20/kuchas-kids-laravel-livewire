<?php

namespace App\Http\Livewire;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Livewire\Component;

class ProductView extends Component
{
    public $product, $variant, $images = [], $qs_color, $qs_size;

    public $select_color_id = null, $select_size_id = null, $sizes = [], $colors = [];

    public $isLoaded = false;

    protected $queryString = [
        'qs_color' => ['as' =>  'c'],
        'qs_size' =>  ['as' =>  't'],
    ];

    public function handleSelectColor(Color $color)
    {
        $this->select_color_id = $color->id;
        $this->qs_color = $color->slug;
        $this->variant = takeFirstVariant($this->product, $this->select_color_id);

        $prev = $this->variant->image_product->map(fn ($img) => $img->url);
        if (count($prev) > 0) $this->images = $prev;

        $this->makePrices();
        $this->emitTo('add-cart-item', 'mount');
        $this->emit('swiperRefresh');

        $this->isLoaded = true;
    }

    public function handleSelectSize(Size $size)
    {
        $this->select_size_id = $size->id;
        $this->qs_size = $size->slug;
        $this->variant = $this->product->product_size()->where('status', Product::PUBLICADO)->where('size_id', $size->id)->first();
        $prev = collect(json_decode(@$this->variant->gallery));
        if (count($prev) > 0) $this->images = $prev;

        $this->emitTo('add-cart-item', 'mount');
        $this->emit('swiperRefresh');
    }

    public function makePrices()
    {
        if ($this->product->type_variant == Product::VARBASE) {
            [$price, $offer_price] =  showPricesProduct($this->product, $this->variant);
            $this->variant->price = $price;
            $this->variant->offer_price = $offer_price;
        }
    }

    public function loadGallery()
    {
        $this->emit('swiperRefresh');
    }

    public function mount()
    {

        switch ($this->product->type_variant) {
            case Product::VARBASE:
                $this->variant = $this->product;
                break;
            case Product::VARCOLORS:
                $this->colors = Color::whereIn('id', $this->product->color_product()->where('status', Product::PUBLICADO)->pluck('color_id'))->get();
                if ($this->qs_color) {
                    $fcolor = Color::where('slug', $this->qs_color)->first();
                    $this->handleSelectColor($fcolor);
                }
                break;
            case Product::VARSIZES:
                $this->sizes = Size::whereIn('id', $this->product->product_size()->where('status', Product::PUBLICADO)->pluck('size_id'))->get();
                if ($this->qs_size) {
                    $fsize = Size::where('slug', $this->qs_size)->first();
                    $this->handleSelectSize($fsize);
                }
                break;
        }

        // if (in_array($this->product->type_variant, [Product::VARCOLORS, Product::VARCOLORSSIZES])) {
        //     $c_prod_img = $this->product->color_product()->where('status', Product::PUBLICADO)->where('color_id', $this->select_color_id)->first();
        //     $this->images = $c_prod_img->image_product->map(function ($img) {
        //         return $img->url;
        //     });
        // }
    }

    public function render()
    {
        return view('livewire.product-view');
    }
}
