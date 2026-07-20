<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryFilter extends Component
{
  use WithPagination;

  public $category, $subcategoria, $marca, $showButton = false;
  public $perPage = 20;
  public $page = 1;

  public $view = "grid";
  // list

  protected $queryString = [
    'subcategoria',
    'marca',
    'page'
  ];

  public function resetFilters()
  {
    $this->reset(['subcategoria', 'marca', 'page']); // elimina la paginacion
    $this->showButton = false;
  }

  public function updatingSubcategoria()
  {
    $this->resetPage();
    $this->showButton = true;
    $this->emit('downPage', 500);
  }

  public function updatingMarca()
  {
    $this->resetPage();
    $this->showButton = true;
  }

  public function gotoPage($page)
  {
    $this->setPage($page);
    $this->emit('gotoTop');
  }

  public function nextPage()
  {
    $this->setPage($this->page + 1);
    $this->emit('gotoTop');
  }

  public function previousPage()
  {
    $this->setPage(max($this->page - 1, 1));
    $this->emit('gotoTop');
  }

  public function mount()
  {
    $subcategories = $this->category->subcategories()->where('subcategories.status', Subcategory::PUBLIC)->select('subcategories.*')->get();

    $seoItems = collect([]);
    foreach ($subcategories as $key => $subcategory) {
      if (!empty($subcategory->keywords))  $seoItems->push($subcategory->keywords);
    }
    $seoItems->push($this->category->brands()->select('brands.name')->get()->pluck('name')->toArray());
    $seoItems->push($subcategories->map(fn ($subcategory) => $subcategory->name)->toArray());


    $seoItems = $seoItems->flatten();
    $seoItemsArray = $seoItems->toArray();

    $lastKey = array_key_last($seoItemsArray);

    $description = '';
    foreach ($seoItems as $key => $seoItem) {
      $description .= $seoItem;
      if ($key !== $lastKey) $description .= ',';
    }

    // URL canónica sin query params de filtro/paginación (evita contenido duplicado indexado)
    setSEOTools($this->category->name, $description, url()->current());
  }

  public function render()
  {
    $productsQuery = Product::query()->whereHas('subcategory', function (Builder $query) {
      $query->where('status', Subcategory::PUBLIC);
    })->whereHas('subcategory.category', function (Builder $query) {
      $query->where('id', $this->category->id);
    });

    $colorAttrId = \App\Models\Attribute::where('name', 'Color')->value('id');

    $productsQuery = $productsQuery->with([
        'subcategory',
        'variants' => function($q) {
            $q->where('status', true);
        },
        'variants.attributeOptions' => function($q) use ($colorAttrId) {
            if ($colorAttrId) $q->where('attribute_id', $colorAttrId);
        }
    ]);

    $subcategories = $this->category->subcategories()->where('status', Subcategory::PUBLIC)->orderBy('position')->get();

    if ($this->subcategoria) {
      $productsQuery = $productsQuery->whereHas('subcategory', function (Builder $query) {
        $query->where('slug', $this->subcategoria);
      });
      $this->showButton = true;
    }

    if ($this->marca) {
      $productsQuery = $productsQuery->whereHas('brand', function (Builder $query) {
        $query->where('name', $this->marca);
      });
      $this->showButton = true;
    }

    $productsQuery = $productsQuery->where('status', Product::PUBLICADO);
    
    // Sort and get results
    $allProducts = $productsQuery->get()->sortBy([['subcategory.position'], ['position']]);

    // Manual Pagination
    $startIndex = ($this->page - 1) * $this->perPage;
    $paginatedProducts = $allProducts->slice($startIndex, $this->perPage);

    // Pre-procesar datos para cada producto en la página actual (Evita N+1 en product-card)
    $paginatedProducts = $paginatedProducts->map(function($product) use ($colorAttrId) {
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

    $paginator = new LengthAwarePaginator(
      $paginatedProducts,
      $allProducts->count(),
      $this->perPage,
      $this->page
    );

    return view('livewire.category-filter', compact('paginatedProducts', 'paginator', 'subcategories'));
  }
}
