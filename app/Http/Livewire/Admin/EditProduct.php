<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class EditProduct extends Component
{
    public $product, $categories, $subcategories, $brands, $slug;

    public $category_id;

    public $colors = [], $sizes = [];

    public $options = '', $color_selected = "",
        $size_selected = "", $quantity_color = "",
        $quantity = "",
        $quantity_size = "", $quantity_color_size = ""; // variaciones para el producto

    public $options_colors, $options_sizes, $options_colors_sizes;

    public $price_size, $offer_price_size, $price_color_size, $offer_price_color_size;

    protected $listeners = ['refreshImages', 'delete', 'changeVariant'];

    protected $rules = [
        // se sincroniza con el $product que estoy pasando y se pueda ver
        'category_id'            => 'required',
        'product.subcategory_id' => 'required',
        'product.name'           => 'required',
        'slug'                   => 'required|unique:products,slug', // deberia  buscar la columna slug
        'product.description'    => 'required',
        'product.quantity'       => '',
        'product.brand_id'       => '',
        'product.price'          => 'required|numeric|min:2',
        'product.offer_price'    => 'nullable|lt:product.price',
    ];

    protected $validationAttributes = [
        'category_id'            => 'categoría',
        'subcategory_id'         => 'subcategoría',
        'product.name'           => 'nombre',
        'product.description'    => 'descripción',
        'product.price'          => 'precio',
        'product.brand_id'       => 'marca',
        'product.offer_price'    => 'precio oferta',
        'product.quantity'       => 'cantidad',
        'offer_price_size'       => 'precio oferta',
        'offer_price_color_size' => 'precio oferta',
        'quantity_color'         => 'cantidad',
        'quantity_size'          => 'cantidad',
        'quantity_color_size'    => 'cantidad',
        'color_selected'         => 'color',
        'size_selected'          => 'talla',
    ];

    public function updatingProductName($value)
    {
        $this->slug = Str::slug($value) ?: '';
    }

    public function updatingCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();

        $this->brands = Brand::whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        })->get();

        $this->product->subcategory_id = "";
        $this->product->brand_id = "";
    }

    public function updatingOptions($value)
    {
        $this->resetOptions();

        $this->reset([
            'color_selected',
            'size_selected',
            'quantity_color',
            'quantity_size',
            'quantity_color_size'
        ]);

        if ($value)
            $this->quantity = '';
        // switch ($value) {
        //     case 'colors':
        //         $this->getColors();
        //         break;
        //     case 'sizes':
        //         $this->getSizes();
        //         $this->price = '';
        //         break;
        //     case 'colors_sizes':
        //         $this->getColors();
        //         $this->getSizes();
        //         $this->price = '';
        //         break;
        //     default:
        //         break;
        // }
    }

    // public function getColors()
    // {
    //     $this->colors = Color::orderBy('name', 'ASC')->get();
    // }

    // public function getSizes()
    // {
    //     $this->sizes = Size::orderBy('name', 'ASC')->get();
    // }

    public function addOptionColor()
    {
        $this->validate([
            'color_selected' => 'required',
            'quantity_color' => 'required|min:0|numeric',
        ]);
        $exist = false;
        // LOOP PARA VERIFICAR SI EXISTE UN COLOR
        $this->options_colors = $this->options_colors->map(function ($op_color, $key) use (&$exist) {
            if ($this->color_selected == $op_color['color_id']) {
                $exist = true;
                $op_color['quantity'] += $this->quantity_color;
            }
            return $op_color;
        });

        if (!$exist) {
            $el = [
                'color_id' => $this->color_selected,
                'quantity' => floatval($this->quantity_color),
            ];
            $this->options_colors->push($el);
        }

        $this->reset(['color_selected', 'quantity_color']);
    }

    // public function deleteOptionColor($pos)
    // {
    //     $this->options_colors->splice($pos, 1);
    // }

    // public function addOptionSize()
    // {
    //     $this->validate([
    //         'size_selected' => 'required',
    //         'quantity_size' => 'required|min:0|numeric',
    //         'price_size' => 'required|min:2|numeric',
    //         'offer_price_size' => 'lt:price_size',
    //     ]);
    //     $exist = false;
    //     // LOOP PARA VERIFICAR SI EXISTE UNA TALLA
    //     $this->options_sizes = $this->options_sizes->map(function ($op_size, $key) use (&$exist) {
    //         if ($this->size_selected == $op_size['size_id']) {
    //             $exist = true;
    //             $op_size['quantity'] += $this->quantity_size;
    //             $op_size['price'] = $this->price_size;
    //             $op_size['offer_price'] = $this->offer_price_size;
    //         }
    //         return $op_size;
    //     });

    //     if (!$exist) {
    //         $el = [
    //             'size_id' => $this->size_selected,
    //             'quantity' => floatval($this->quantity_size),
    //             'price' => $this->price_size,
    //             'offer_price' => $this->offer_price_size,
    //         ];
    //         $this->options_sizes->push($el);
    //     }

    //     $this->reset(['size_selected', 'quantity_size', 'price_size', 'offer_price_size']);
    // }

    // public function deleteOptionSize($pos)
    // {
    //     $this->options_sizes->splice($pos, 1);
    // }

    // public function addOptionColorSize()
    // {
    //     $this->validate([
    //         'color_selected' => 'required',
    //         'size_selected' => 'required',
    //         'quantity_color_size' => 'required|min:0|numeric',
    //         'price_color_size' => 'required|min:2|numeric',
    //         'offer_price_color_size' => 'lt:price_color_size',
    //     ]);
    //     $exist = false;
    //     // LOOP PARA VERIFICAR SI EXISTE UNA TALLA
    //     $this->options_colors_sizes = $this->options_colors_sizes->map(function ($op_color_size, $key) use (&$exist) {
    //         if ($this->color_selected == $op_color_size['color_id'] && $this->size_selected == $op_color_size['size_id']) {
    //             $exist = true;
    //             $op_color_size['quantity'] += $this->quantity_color_size;
    //             $op_color_size['price'] = $this->price_color_size;
    //             $op_color_size['offer_price'] = $this->offer_price_color_size;
    //         }
    //         return $op_color_size;
    //     });

    //     if (!$exist) {
    //         $el = [
    //             'color_id' => $this->color_selected,
    //             'size_id' => $this->size_selected,
    //             'quantity' => floatval($this->quantity_color_size),
    //             'price' => $this->price_color_size,
    //             'offer_price' => $this->offer_price_color_size,
    //         ];
    //         $this->options_colors_sizes->push($el);
    //     }

    //     $this->reset(['color_selected', 'size_selected', 'quantity_color_size']);
    // }

    // public function deleteOptionColorSize($pos)
    // {
    //     $this->options_colors_sizes->splice($pos, 1);
    // }

    // prop computada
    public function getSubcategoryProperty()
    {
        // puede haber un null
        return Subcategory::find($this->product->subcategory_id);
    }

    public function resetOptions()
    {
        $this->options_colors = collect();
        $this->options_sizes = collect();
        $this->options_colors_sizes = collect();
    }

    public function deleteImage(Image $image)
    {
        Storage::delete([$image->url]);
        $image->delete();

        $this->product = $this->product->fresh();
    }

    public function refreshImages()
    {
        $this->product = $this->product->fresh();
    }

    public function delete()
    {
        $images = $this->product->images;
        // TODO: cuando sea por color que tambien se eliminen dichas imagenes.
        foreach ($images as $image) {
            Storage::delete($image->url);
            $image->delete();
        }

        $this->product->saveDelete();

        return redirect()->route('admin.index');
    }

    public function save()
    {
        $rules = $this->rules;
        $rules['slug'] = 'required|unique:products,slug,' . $this->product->id; // ignora el id que se envia del mismo para que no retorne repetido

        if (count($this->brands) > 0) {
            $rules['product.brand_id'] = 'required';
        } else {
            $this->product->brand_id = null;
        }

        if (count($this->product->color_product) == 0 && count($this->product->product_size) == 0 && count($this->product->color_product_size) == 0) {
            $rules['product.quantity'] = 'required|numeric|min:1';
        }

        $this->validate($rules);

        $this->product->slug = $this->slug;

        if ($this->product->offer_price == '') $this->product->offer_price = null;

        $this->product->save();

        $this->emit('saved');
    }

    public function changeVariant($newValue, $confirm)
    {
        if (!$confirm) {
            return $this->mount($this->product);
        }

        $this->product->deleteVariants($newValue);
    }

    public function mount(Product $product)
    {
        $this->product = $product;

        $this->categories = Category::all();

        $this->category_id = $product->subcategory->category->id;

        $this->subcategories = Subcategory::where('category_id', $this->category_id)->get();

        $this->slug = $this->product->slug;

        $this->brands = Brand::whereHas('categories', function (Builder $query) {
            $query->where('category_id', $this->category_id); // match con el category_id
        })->get();

        // VALIDAR SI TIENE VARIANTES
        // if (count($this->product->sizes) == 0 &&  count($this->product->colors) == 0) {

        if (count($this->product->color_product) > 0) {
            $this->options = 'colors';
        } else if (count($this->product->product_size) > 0) {
            $this->options = 'sizes';
        } else if (count($this->product->color_product_size) > 0) {
            $this->options = 'colors_sizes';
        } else {
            $this->options = 'base';
        }
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }
}
