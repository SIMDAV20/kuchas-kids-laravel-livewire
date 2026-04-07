<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\PaymentOrder;
use App\Http\Livewire\ShoppingCart;
use App\Http\Controllers\OrderController;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SecondaryPagesController;
use App\Http\Controllers\WebhooksController;
use App\Http\Livewire\ContactForm;
use App\Http\Livewire\ComplaintsBook;

Route::get('/', WelcomeController::class)->name('welcome');

Route::get('search', SearchController::class)->name('search');

// Páginas de información dinámicas servidas desde resources/markdown/pages/{page}.md
Route::get('info/{page}',            [SecondaryPagesController::class, 'markdownPage'])->name('info.page');

// muestra el id de la categoria
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('products/{slugProduct}', [ProductController::class, 'showProduct'])->name('products.show');

Route::get('contact', ContactForm::class)->name('contact.index');
Route::get('libro-de-reclamaciones', ComplaintsBook::class)->name('complaints-book');

// administrado por livewire
Route::get('shopping-cart', ShoppingCart::class)->name('shopping-cart');

Route::middleware(['auth'])->group(function () {
    //  php artisan make:policy OrderPolicy para restrigir los usuarios por su order

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    // administrado por livewire
    Route::get('orders/create', CreateOrder::class)->name('orders.create');

    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/annuled', [OrderController::class, 'annuled'])->name('orders.annuled');

    // administrado por livewire
    Route::get('orders/{order}/payment', PaymentOrder::class)->name('orders.payment');

    // solo para el desarrollo
    Route::get('orders/pay', [OrderController::class, 'pay'])->name('orders.pay');

    Route::post('orders/izipay', [OrderController::class, 'izipay'])->name('orders.izipay');

    // Route::get('orders/izipay', [OrderController::class, 'izipay'])->name('orders.izipay');

    Route::post('webhooks', [WebhooksController::class])->name('webhooks.pay');

    Route::get('orders/failure', [OrderController::class, 'orderFailure'])->name('orders.failure');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('destroy', function () {
    Cart::destroy();
});

// Route::get('linkstorage', function () {
//     \Artisan::call('storage:link');
//     dd("Cache is cleared");
// });

// Route::get('clear_cache', function () {

//     \Artisan::call('cache:clear');

//     dd("Cache is cleared");
// });

// Route::get('clear_config', function () {

//     \Artisan::call('config:clear');

//     dd("config is cleared");
// });

// Route::get('/prueba', function () {
//     $days = now()->subDay(5);

//     $orders = Order::where('status', 1)->whereTime('created_at', '<=', $days)->get();

//     foreach ($orders as $order) {

//         $items = json_decode($order->content);

//         foreach ($items as $item) {
//             increase($item);
//         }

//         $order->status = 5;
//         $order->save();
//     }

//     return 'success';
// });
