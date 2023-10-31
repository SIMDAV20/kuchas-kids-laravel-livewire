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

  public function delete(Product $product)
  {
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

  // [{"id":1,"sku":null,"slug":"plato-manzanita-rosado","gallery":["products\/ZEWBfkEGsAcBiMZpagvjRirTrQIOeVx3whssUR49.jpg","products\/JCMXfhNWD2yDehNLIhKW6K2ildENHcXsXNu3nYfG.jpg","products\/vcnwo5FLhxPl5dXn55qEoqza8tbxdvUSk7ypNK5H.jpg","products\/frtjigSHkKYSNSahyR0JQUVGra52tlv9BIDSCAA9.jpg"],"status":"2","quantity":150,"price":"0.00","offer_price":null,"color_id":1,"product_id":1,"created_at":"2022-03-14T12:05:59.000000Z","updated_at":"2023-10-30T05:49:58.000000Z"},{"id":2,"sku":null,"slug":"plato-manzanita-verde","gallery":["products\/VNNoXsQBYItcAuvQQCn6hpYWZVCqKfEqG94jhrXK.jpg","products\/qchbEqgiGfIV9bTEgEZFHRVq68tnmBp5si6eociL.jpg","products\/jwvvxQfmsQofEpX51Vcu6hglmdB8vu51y9u4Jn7t.jpg","products\/DspU5zOUCULgT45PTVzSCcH2BrgwgauRaGm3ATLo.jpg"],"status":"2","quantity":150,"price":"0.00","offer_price":null,"color_id":2,"product_id":1,"created_at":"2022-03-14T12:05:59.000000Z","updated_at":"2023-10-30T05:49:58.000000Z"}]
  // {"id":23,"sku":null,"name":"Colitas de dinosaurios","slug":"colitas-de-dinosaurios",
  //   "gallery":"products\/56JJ5RFBWl9bsKKBuNBdqacNi40o8dbmZUTsLIL0.jpg","type_variant":"base","quantity":48,"price":"61.00","offer_price":"50.00","offer_date":null,"video":"https:\/\/www.youtube.com\/embed\/NogOjL3RlRY","description":"<p>\u201cAprende mientras juega\u201d.<\/p><p>Aumente la interacci\u00f3n entre padres e hijos y desarrolla las habilidades del idioma ingl\u00e9s, habilidades de comunicaci\u00f3n, imaginaci\u00f3n y sensoriales.<\/p><p><br><\/p><p>M\u00e1s detalles:<br><\/p><p>- Libro de tela de educaci\u00f3n temprana de colitas de animales<\/p><p>- Material: Poli\u00e9ster, PET.<\/p><p>- Rango de edad: 0 a 24 meses.<\/p><p>- Colas de diferentes materiales, formas y colores<\/p><p>- Entrenamiento de audici\u00f3n, entrenamiento de agarre<\/p><p>- Al estrujar la primera y \u00faltima p\u00e1gina se produce un sonido crujiente<\/p><p>- Resistente al desgarro, costuras ajustadas y duraderas<\/p><p>- El color no se desvanece despu\u00e9s del lavado, material seguro e inodoro.&nbsp;<\/p><p>- Tama\u00f1o: 22cm * 12cm * 4cm.<\/p>","status":"2","subcategory_id":7,"brand_id":2,"created_at":"2022-03-14T13:05:59.000000Z","updated_at":"2023-10-30T05:49:58.000000Z","color_product":[],"product_size":[],"color_product_size":[]}


  //   {"id":5,"sku":null,"name":"Babero con bolsillo - Dinosaurio","slug":"babero-con-bolsillo-dinosaurio",
  //     "gallery":["products\/zrsZRkg07foVjTZPSXZSWp0eocUZfBM4UhrZPzni.jpg",
  //     "products\/7O2oUSo4g2oZALrrI8WX6r0wqdhAMRBGK3ZAuD55.jpg"],
  //     "type_variant":
  //     "colors","quantity":null,"price":"36.00","offer_price":"28.00","offer_date":null,"video":null,"description":"<p>M\u00e1s detalles:<\/p><p>- Para beb\u00e9s a partir de los 6 meses.<\/p><p>- Babero de silicona suave, c\u00f3modo y ecol\u00f3gico.<\/p><p>- De f\u00e1cil limpieza: antibacteriano, lavable e impermeable.<\/p><p>- Ajustable (6 posiciones de ajuste).<\/p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames l\u00edquidos mientras los ni\u00f1os comen.<\/p><p>- Tama\u00f1o: 30 largo x 23 ancho aprox. (cm)<\/p>","status":"2","subcategory_id":3,"brand_id":5,"created_at":"2022-03-14T13:05:59.000000Z","updated_at":"2023-10-30T05:49:57.000000Z","color_product":[{"id":7,"sku":null,"slug":"babero-con-bolsillo-dinosaurio-verde-kaki","gallery":["products\/zrsZRkg07foVjTZPSXZSWp0eocUZfBM4UhrZPzni.jpg","products\/7O2oUSo4g2oZALrrI8WX6r0wqdhAMRBGK3ZAuD55.jpg"],
  //     "status":"2","quantity":50,"price":"0.00","offer_price":null,"color_id":12,"product_id":5,
  //     "created_at":"2022-03-14T12:05:59.000000Z","updated_at":"2023-10-30T05:49:58.000000Z"}],
  //   "product_size":[],"color_product_size":[]}



  public function render()
  {
    $products = Product::with(
      'color_product',
      'product_size',
      'color_product_size',
    )->where('name', 'like', '%' . $this->search . '%')
      ->orderBy('id', 'desc')
      ->paginate(10);

    foreach ($products as $key => $prod) {
      if ($prod->type_variant == Product::VARBASE) {
        $prod->gallery = @$prod->gallery[0];
      }

      if ($prod->type_variant == Product::VARCOLORS) {
        $prod->gallery =  @$prod->color_product->first()->gallery[0];
      }

      if ($prod->type_variant == Product::VARSIZES) {
        $prod->gallery =  @$prod->product_size->first()->gallery[0];
      }

      if ($prod->type_variant == Product::VARCOLORSSIZES) {
        $prod->gallery =  @$prod->color_product_size->first()->gallery[0];
      }
    }


    return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
  }
}
