<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

use Livewire\WithPagination;

class ShowProducts extends Component
{
  use WithPagination;

  public $search;

  protected $listeners = ['delete'];

  protected $queryString = [
    'search',
    'page'
  ];

  public function delete(Product $product)
  {

    // TODO  MAKE OBSERVER
    if (count($product->color_product_size)) {
      // eliminar las relaciones del producto por color y talla
      $product->color_product_size()->detach();
    } else if (count($product->product_size)) {
      // eliminar las relaciones del producto por talla
      $product->product_size()->detach();
    } else if (count($product->color_product)) {
      // eliminar las relaciones del producto por color
      $product->color_product()->detach();
    }
    $product->delete();
  }

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function mount()
  {
    $this->search = request()->query('search', $this->search);
  }

  public function render()
  {
    $products = Product::searchAll($this->search)
      ->orderBy('id', 'desc')
      ->paginate(10)
      ->withQueryString($this->queryString)
      ->setPath(route('admin.index'));

    // Append the current query string parameters to the pagination links
    $products->appends(['search' => $this->search]);

    return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
  }
}
