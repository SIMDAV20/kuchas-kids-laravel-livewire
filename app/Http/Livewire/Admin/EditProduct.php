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
    
    // --- PROPIEDADES PARA IMÁGENES DE VARIANTE ---
    public $isImageModalOpen = false;
    public $editingVariantImagesId = null;
    public $selectedImageIds = [];

    // --- SELECCIÓN MASIVA DE VARIANTES ---
    public $selectedVariants = [];
    public $selectAllVariants = false;

    protected $listeners = ['refreshImages', 'deleteProduct' => 'delete', 'updateVariant', 'deleteVariant'];

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
        $this->product = $product->load('images_morph');
        $this->categories = Category::all();
        $this->category_id = $product->subcategory->category->id;
        $this->subcategories = Subcategory::where('category_id', $this->category_id)->get();
        $this->slug = $this->product->slug;
        $this->brands = Brand::whereHas('categories', function (Builder $query) {
            $query->where('category_id', $this->category_id);
        })->get();

        $this->allAttributes = Attribute::with('options')->get();
        foreach ($this->allAttributes as $attribute) {
            $this->selectedAttributes[$attribute->id] = [];
        }
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

            // Evitar duplicados por slug
            $existingVariant = ProductVariant::where('slug', $variantSlug)->first();
            if (!$existingVariant) {
                $variant = ProductVariant::create([
                    'product_id'  => $this->product->id,
                    'slug'        => $variantSlug,
                    'sku'         => null,
                    'price'       => $this->product->price ?? 0,
                    'offer_price' => $this->product->offer_price ?? 0,
                    'stock'       => 0,
                    'status'      => true
                ]);

                // Sincronizar las opciones del atributo
                $optionIds = array_map(fn($opt) => $opt->id, $combination);
                $variant->attributeOptions()->attach($optionIds);
            }
        }

        $this->selectedAttributes = $this->allAttributes->mapWithKeys(fn($attr) => [$attr->id => []])->toArray();
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

    public function openImageModal($variantId)
    {
        $this->editingVariantImagesId = $variantId;
        $variant = ProductVariant::find($variantId);
        
        $this->selectedImageIds = $variant->images ?? [];
        $this->isImageModalOpen = true;
    }

    public function toggleImageSelection($imageId)
    {
        if (in_array($imageId, $this->selectedImageIds)) {
            $this->selectedImageIds = array_diff($this->selectedImageIds, [$imageId]);
        } else {
            $this->selectedImageIds[] = $imageId;
        }
    }

    public function saveVariantImages()
    {
        if ($this->editingVariantImagesId) {
            $variant = ProductVariant::find($this->editingVariantImagesId);
            if ($variant) {
                // Ensure arrays are reindexed
                $variant->images = array_values($this->selectedImageIds);
                $variant->save();
            }
        }
        $this->isImageModalOpen = false;
        $this->editingVariantImagesId = null;
        $this->product->load('variants');
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

    public function updatedSelectAllVariants($value)
    {
        if ($value) {
            $this->selectedVariants = $this->product->variants->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedVariants = [];
        }
    }

    public function activateSelectedVariants()
    {
        ProductVariant::whereIn('id', $this->selectedVariants)->update(['status' => true]);
        $this->reset(['selectedVariants', 'selectAllVariants']);
        $this->product->load('variants');
    }

    public function deactivateSelectedVariants()
    {
        ProductVariant::whereIn('id', $this->selectedVariants)->update(['status' => false]);
        $this->reset(['selectedVariants', 'selectAllVariants']);
        $this->product->load('variants');
    }

    public function deleteSelectedVariants()
    {
        ProductVariant::whereIn('id', $this->selectedVariants)->delete();
        $this->reset(['selectedVariants', 'selectAllVariants']);
        $this->product->load('variants');
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }
}
