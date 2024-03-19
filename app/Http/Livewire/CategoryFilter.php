<?php

namespace App\Http\Livewire;

use App\Models\Product;
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
        $seoItems[] = $this->category->subcategories()->select('subcategories.name')->get();
        $seoItems[] = $this->category->brands()->select('brands.name')->get();
        $seoItems[] = $this->category->products()->where('status', Product::PUBLICADO)->select('products.name')->get();

        $description = '';
        foreach ($seoItems as $key => $seoItem) {
            $description .= $seoItem->implode('name', ',') . ($key === array_key_last($seoItems) ? '' : ',');
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
        $productsQuery = Product::query()->whereHas('subcategory.category', function (Builder $query) {
            $query->where('id', $this->category->id);
        })->with('subcategory');

        $subcategories = $this->category->subcategories()->orderBy('position')->get();

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
