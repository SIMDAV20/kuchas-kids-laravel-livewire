<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Livewire\Admin\BrandComponent;
use App\Http\Livewire\Admin\CityComponent;
use App\Http\Livewire\Admin\CreateProduct;
use App\Http\Livewire\Admin\DepartmentComponent;
use App\Http\Livewire\Admin\ShowProducts;
use App\Http\Livewire\Admin\EditProduct;
use App\Http\Livewire\Admin\ShowCategory;
use App\Http\Livewire\Admin\ShowCity;
use App\Http\Livewire\Admin\ShowDepartment;
use App\Http\Livewire\Admin\UserComponent;
use App\Http\Livewire\Admin\DeliveryZone;
use App\Http\Livewire\Admin\ManageColorsSizes;
use App\Http\Livewire\Admin\SettingsComponent;
use App\Http\Livewire\Admin\UploadBanner;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowProducts::class)->name('admin.index');

Route::get('products/create', CreateProduct::class)->name('admin.products.create');
Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');
Route::post('products/{product}/files', [ProductController::class, 'files'])->name('admin.products.files');

Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
Route::get('categories/{category}', ShowCategory::class)->name('admin.categories.show');

Route::get('brands', BrandComponent::class)->name('admin.brands.index');

Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');

// Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

// Route::get('departments', DepartmentComponent::class)->name('admin.departments.index');
// Route::get('departments/{department}', ShowDepartment::class)->name('admin.departments.show');
// Route::get('provinces/{city}', ShowProvince::class)->name('admin.provinces.show');

Route::get('delivery-zone', DeliveryZone::class)->name('admin.zones.index'); // para dar el costo por distrito

Route::get('colors-sizes', ManageColorsSizes::class)->name('admin.colors-sizes.index');

Route::get('users', UserComponent::class)->name('admin.users.index');

Route::get('settings', SettingsComponent::class)->name('admin.settings.index');

Route::get('/banners', UploadBanner::class)->name('admin.banners.index');
