<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;

class WelcomeController extends Controller
{
  public function __invoke()
  {

    $seoItems = collect([]);
    $subcategories = Subcategory::orderBy('position', 'ASC')->get();
    foreach ($subcategories as $key => $subcategory) {
      if (!empty($subcategory->keywords))  $seoItems->push($subcategory->keywords);
    }
    // $seoItems[] = $categories;
    // $seoItems[] = Subcategory::select('name')->get();
    // $seoItems[] = Product::where('status', Product::PUBLICADO)->select('name', 'created_at')->orderBy('created_at', 'DESC')->get();

    $description = '';
    $seoItems = $seoItems->flatten()->unique()->values();
    $description .= $seoItems->implode(',');
    setSEOTools(null,  $description);

    if (auth()->user()) {
      $orders = Order::where('status', 1)
        ->whereNull('payment_method')
        ->where('user_id', auth()->user()->id)
        ->get();
      $pendiente = $orders->count();
      if ($pendiente) {
        $mensaje = "Usted tiene $pendiente órdenes pendientes . <a class='font-bold' href='" . route('orders.index') . "?status=1'>Ir a pagar</a>";
        // generar el mensaje flash
        session()->flash('flash.banner', $mensaje);
      }
    }

    $categories = Category::orderBy('position', 'ASC')->where('status', Category::PUBLIC)->get();
    return view('welcome', compact('categories'));
  }
}
