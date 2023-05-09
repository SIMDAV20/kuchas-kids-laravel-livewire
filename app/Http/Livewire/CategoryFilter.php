<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryFilter extends Component
{
    use WithPagination;

    public $category, $subcategoria, $marca, $showButton = false;

    public $view = "grid";
    // list

    protected $queryString = ['subcategoria', 'marca'];

    public function limpiar()
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

    public function render()
    {
        // $products = $this->category->products()
        //             ->where('status', 2)
        //             ->paginate(15);

        // whereHas es si existe la relacion en los modelos
        // $productsQuery es la consulta

        $productsQuery = Product::query()->whereHas('subcategory.category', function (Builder $query) {
            $query->where('id', $this->category->id);
        });

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

        $products = $productsQuery->where('status', 2)->paginate(20);
        return view('livewire.category-filter', compact('products'));
    }
}
