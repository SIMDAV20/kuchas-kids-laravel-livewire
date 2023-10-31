<?php

namespace App\Http\Livewire\Admin;

use App\Models\ColorProduct;
use App\Models\Image;
use App\Models\ImageProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class GlobalGallery extends Component
{

  use WithFileUploads;

  public $photo, $images = [], $selectedImages = [];

  public $item, $item_id, $model, $type_variant, $open_gallery = false;

  protected $listeners = ['delete'];

  protected $rules = [
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];

  public function edit()
  {
    // $this->item = findProduct($this->model, $this->item_id);
    // $this->images = $this->item->images;
    $this->open_gallery = true;
  }

  public function uploadImage()
  {
    $this->validate();

    $url = Storage::put('products', $this->photo);

    $img = new ImageProduct();
    $img->url = $url;
    $img->save();

    $this->refreshImages();
  }

  public function update()
  {
    $this->item->gallery = json_encode($this->selectedImages);
    $this->item->save();
    $this->open_gallery = false;
  }

  public function delete(Image $image)
  {
    if (Storage::exists($image->url)) {
      Storage::delete($image->url); // ruta de la photo
    }
    $image->delete();
    $this->refreshImages();
  }

  public function refreshImages()
  {
    $this->getImageProducts();
    $this->photo = null;
  }

  public function getImageProducts()
  {
    $this->images = ImageProduct::orderBy('created_at', 'DESC')->get();
  }

  public function mount()
  {
    $model_str = [
      Product::VARBASE => 'Product',
      Product::VARCOLORS => 'ColorProduct',
      Product::VARSIZES => 'ProductSize',
      Product::VARCOLORSSIZES => 'ColorProductSize',
    ];

    $model_name = '\\App\\Models\\' . $model_str[$this->type_variant];
    $this->model = new $model_name;
    $this->item = $this->model::find($this->item_id);
    $this->getImageProducts();
    $this->selectedImages = json_decode($this->item->gallery) ?? [];
  }

  public function render()
  {
    return view('livewire.admin.global-gallery');
  }
}
