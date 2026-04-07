<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\ProductVariant;
use Livewire\Component;

class ProductDetail extends Component
{
    public $product;
    
    // State of selections
    public $selectedColorId;
    public $selectedSizeId;
    
    // Current data
    public $currentVariant;
    public $price;
    public $offerPrice;
    public $stock;
    public $quantity = 1;
    
    // Gallery
    public $currentImages = [];
    
    // Flash Offer
    public $flashOffer;
    public $timeRemaining;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->loadInitialState();
    }

    public function loadInitialState()
    {
        // Check if it has variants
        if ($this->product->variants->count() > 0) {
            // Find first active variant to show initial price
            $this->currentVariant = $this->product->variants->where('status', true)->first();
            
            if ($this->currentVariant) {
                $this->price = $this->currentVariant->price;
                $this->offerPrice = $this->currentVariant->offer_price;
                $this->stock = $this->currentVariant->stock;
                
                // Pre-select attributes if any
                $colorAttr = Attribute::where('name', 'Color')->first();
                if ($colorAttr) {
                    $colorOpt = $this->currentVariant->attributeOptions->where('attribute_id', $colorAttr->id)->first();
                    $this->selectedColorId = $colorOpt ? $colorOpt->id : null;
                }

                $sizeAttr = Attribute::where('name', 'Talla')->first();
                if ($sizeAttr) {
                    $sizeOpt = $this->currentVariant->attributeOptions->where('attribute_id', $sizeAttr->id)->first();
                    $this->selectedSizeId = $sizeOpt ? $sizeOpt->id : null;
                }
            }
        } else {
            $this->price = $this->product->price;
            $this->offerPrice = $this->product->offer_price;
            $this->stock = $this->product->quantity;
        }

        $this->updateGallery();
        $this->checkFlashOffer();
    }

    public function selectColor($id)
    {
        $this->selectedColorId = $id;
        $this->updateSelection();
    }

    public function selectSize($id)
    {
        $this->selectedSizeId = $id;
        $this->updateSelection();
    }

    public function updateSelection()
    {
        // Buscar la variante que cumpla con los atributos seleccionados
        $query = $this->product->variants()->where('status', true);

        if ($this->selectedColorId) {
            $query->whereHas('attributeOptions', function($q) {
                $q->where('attribute_option_id', $this->selectedColorId);
            });
        }

        if ($this->selectedSizeId) {
            $query->whereHas('attributeOptions', function($q) {
                $q->where('attribute_option_id', $this->selectedSizeId);
            });
        }

        $variant = $query->first();

        if ($variant) {
            $this->currentVariant = $variant;
            $this->price = $variant->price;
            $this->offerPrice = $variant->offer_price;
            $this->stock = $variant->stock;
            $this->updateGallery();
            $this->checkFlashOffer();
        } else {
            $this->currentVariant = null;
            $this->stock = 0;
            // If we selected a color and size doesn't exist, maybe reset size?
        }
    }

    public function updateGallery()
    {
        // Si hay una variante seleccionada y tiene imágenes asignadas (Fase 2)
        if ($this->currentVariant && $this->currentVariant->images && count($this->currentVariant->images) > 0) {
            $this->currentImages = $this->product->images->whereIn('id', $this->currentVariant->images);
        } else {
            // Galería base del producto
            $this->currentImages = $this->product->images;
        }
        
        // Emitir para que el Slider (Glider/FlexSlider) se reinicie si es necesario
        $this->emit('galleryUpdated');
    }

    public function checkFlashOffer()
    {
        // 1. Prioridad: Oferta en la variante
        if ($this->currentVariant) {
            $this->flashOffer = $this->currentVariant->flashOffer()->active()->first();
        }

        // 2. Fallback: Oferta en el producto general
        if (!$this->flashOffer) {
            $this->flashOffer = $this->product->flashOffer()->active()->first();
        }
    }

    public function render()
    {
        // Atributos disponibles (Colors/Sizes)
        $colorAttr = Attribute::where('name', 'Color')->with('options')->first();
        $sizeAttr = Attribute::where('name', 'Talla')->with('options')->first();

        // Filtrar qué colores existen realmente en las variantes de este producto
        $availableColors = collect();
        if ($colorAttr) {
            $availableColors = $this->product->variants()
                ->whereHas('attributeOptions', fn($q) => $q->where('attribute_id', $colorAttr->id))
                ->get()
                ->flatMap(fn($v) => $v->attributeOptions->where('attribute_id', $colorAttr->id))
                ->unique('id');
        }

        // Filtrar qué tallas existen realmente para el color seleccionado (Reactividad Inteligente)
        $availableSizes = collect();
        if ($sizeAttr) {
            $query = $this->product->variants();
            if ($this->selectedColorId) {
                $query->whereHas('attributeOptions', fn($q) => $q->where('attribute_option_id', $this->selectedColorId));
            }
            
            $availableSizes = $query->get()
                ->flatMap(fn($v) => $v->attributeOptions->where('attribute_id', $sizeAttr->id))
                ->unique('id');
        }

        return view('livewire.product-detail', [
            'availableColors' => $availableColors,
            'availableSizes' => $availableSizes,
        ]);
    }
}
