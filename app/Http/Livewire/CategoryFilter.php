<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
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
      if (!empty($subcategory->keywords))  $seoItems->push(json_decode($subcategory->keywords));
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

    $data = [];
    foreach ($this->queryString as $key => $value) {
      $data[$value] = $this->$value;
    }

    if (!is_null($this->page)) {
      $data['page'] = $this->page;
    }

    $query = Arr::query($data);
    $url = url()->current() . ($query ? ('?' . $query) : '');

    setSEOTools($this->category->name, $description, $url);
  }

  public function render()
  {
    $productsQuery = Product::query()->whereHas('subcategory', function (Builder $query) {
      $query->where('status', Subcategory::PUBLIC);
    })->whereHas('subcategory.category', function (Builder $query) {
      $query->where('id', $this->category->id);
    })->with('subcategory');

    $subcategories = $this->category->subcategories()->where('status', Subcategory::PUBLIC)->orderBy('position')->get();

    if ($this->subcategoria) {
      $productsQuery = $productsQuery->whereHas('subcategory', function (Builder $query) {
        $query->where('slug', $this->subcategoria);
      })->orderBy('position');
      $this->showButton = true;
    }


    if ($this->marca) {
      $productsQuery = $productsQuery->whereHas('brand', function (Builder $query) {
        $query->where('name', $this->marca);
      });
      $this->showButton = true;
    }

    $productsQuery = $productsQuery->where('status', Product::PUBLICADO);
    $productsQuery = collect($productsQuery->get())->sortBy([['subcategory.position'], ['position']]);

    $products = $productsQuery;
    // Calcula el índice inicial y final de los elementos en la página actual
    $startIndex = ($this->page - 1) * $this->perPage;
    $endIndex = $startIndex + $this->perPage;

    // Obtiene los elementos para la página actual
    $paginatedProducts = $products->slice($startIndex, $this->perPage);

    // Crea una instancia de LengthAwarePaginator
    $paginator = new LengthAwarePaginator(
      $paginatedProducts,
      $products->count(),
      $this->perPage,
      $this->page
    );

    // $products = $productsQuery->paginate(20);

    return view('livewire.category-filter', compact('paginatedProducts', 'paginator', 'subcategories'));
  }
}
