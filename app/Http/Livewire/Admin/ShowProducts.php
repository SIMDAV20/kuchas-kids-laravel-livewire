<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;

    public $search;
    public $selectedProducts = [];
    public $selectAll = false;
    public $perPage = 25;

    protected $listeners = ['delete', 'render'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'perPage' => ['except' => 25],
    ];

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedProducts = Product::searchAll($this->search)
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    public function publishSelected()
    {
        Product::whereIn('id', $this->selectedProducts)->update(['status' => Product::PUBLICADO]);
        $this->reset(['selectedProducts', 'selectAll']);
    }

    public function draftSelected()
    {
        Product::whereIn('id', $this->selectedProducts)->update(['status' => Product::BORRADOR]);
        $this->reset(['selectedProducts', 'selectAll']);
    }

    public function deleteSelected()
    {
        foreach (Product::whereIn('id', $this->selectedProducts)->get() as $product) {
            $product->saveDelete();
        }
        $this->reset(['selectedProducts', 'selectAll']);
    }

    public function delete(Product $product)
    {
        $product->saveDelete();
    }

    public function changeStatus(Product $product)
    {
        $product->status = ($product->status == Product::BORRADOR) ? Product::PUBLICADO : Product::BORRADOR;
        $product->save();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage($value)
    {
        if (!in_array((int) $value, [25, 50, 100])) {
            $this->perPage = 25;
        }

        $this->resetPage();
    }

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function render()
    {
        $products = Product::searchAll($this->search)
            ->with([
                'subcategory.category',
                'flashOffer',
                'images_morph',
                'variants.flashOffer',
                'variants.attributeOptions.attribute'
            ])
            ->orderBy('id', 'desc')
            ->paginate($this->perPage)->onEachSide(1);

        return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
    }
}
