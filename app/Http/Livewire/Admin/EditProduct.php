<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\ProductVariant;
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

    // --- NUEVAS PROPIEDADES VARIANTES ---
    public $allAttributes;
    public $selectedAttributes = []; // Format: [attribute_id => [option_id, option_id]]
    
    protected $listeners = ['refreshImages', 'deleteProduct' => 'delete', 'updateVariant'];

    protected $rules = [
        'product.subcategory_id' => 'required',
        'product.brand_id'       => 'nullable',
        'product.name'           => 'required',
        'slug'                   => 'required',
        'product.description'    => 'required',
        'product.price'          => 'nullable|numeric',
        'product.offer_price'    => 'nullable|lt:product.price',
        'product.video'          => 'nullable',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->category_id = $product->subcategory->category->id;
        $this->subcategories = Subcategory::where('category_id', $this->category_id)->get();
        $this->slug = $this->product->slug;
        $this->brands = Brand::whereHas('categories', function (Builder $query) {
            $query->where('category_id', $this->category_id);
        })->get();

        $this->allAttributes = Attribute::with('options')->get();
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

    public function updatingProductName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate();
        $this->product->slug = $this->slug;
        $this->product->save();
        $this->emit('saved');
    }

    /**
     * Lógica para GENERAR variantes automáticamente
     * basándose en las opciones seleccionadas
     */
    public function generateVariants()
    {
        // Filtrar atributos que tengan opciones seleccionadas
        $filteredAttributes = array_filter($this->selectedAttributes, fn($options) => count($options) > 0);
        
        if (empty($filteredAttributes)) {
            $this->emit('error', 'Debes seleccionar al menos una opción.');
            return;
        }

        // Obtener las opciones reales de la BD
        $optionsByAttribute = [];
        foreach ($filteredAttributes as $attributeId => $optionIds) {
            $optionsByAttribute[] = AttributeOption::whereIn('id', $optionIds)->get();
        }

        // Calcular Producto Cartesiano para las combinaciones
        $combinations = [[]];
        foreach ($optionsByAttribute as $options) {
            $temp = [];
            foreach ($combinations as $combination) {
                foreach ($options as $option) {
                    $temp[] = array_merge($combination, [$option]);
                }
            }
            $combinations = $temp;
        }

        // Crear las variantes
        foreach ($combinations as $combination) {
            // Generar un SKU/Slug sugerido único: slug-opcion1-opcion2
            $slugParts = [$this->product->slug];
            foreach ($combination as $option) {
                $slugParts[] = Str::slug($option->value);
            }
            $variantSlug = implode('-', $slugParts);

            // Evitar duplicados (Unique Index Logic)
            $existingVariant = ProductVariant::where('sku', $variantSlug)->first();
            if (!$existingVariant) {
                $variant = ProductVariant::create([
                    'product_id' => $this->product->id,
                    'sku' => $variantSlug,
                    'price' => $this->product->price ?? 0,
                    'stock' => 0,
                    'status' => true
                ]);

                // Sincronizar las opciones del atributo
                $optionIds = array_map(fn($opt) => $opt->id, $combination);
                $variant->attributeOptions()->attach($optionIds);
            }
        }

        $this->reset('selectedAttributes');
        $this->product->load('variants');
        $this->emit('variantsGenerated');
    }

    public function updateVariant($variantId, $field, $value)
    {
        $variant = ProductVariant::find($variantId);
        if ($variant) {
            $variant->update([$field => $value]);
        }
    }

    public function deleteVariant($variantId)
    {
        ProductVariant::destroy($variantId);
        $this->product->load('variants');
    }

    public function delete()
    {
        $this->product->delete();
        return redirect()->route('admin.index');
    }

    public function refreshImages()
    {
        $this->product = $this->product->fresh();
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }
}
