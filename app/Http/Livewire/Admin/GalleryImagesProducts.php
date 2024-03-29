<?php

namespace App\Http\Livewire\Admin;

use App\Models\ColorProduct;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalleryImagesProducts extends Component
{
  use WithFileUploads;

  public $photo, $images = [], $item_id, $item, $model, $open_gallery = false;

  protected $listeners = ['delete'];

  protected $rules = [
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];

  public function edit()
  {
    $this->item = findProduct($this->model, $this->item_id);
    $this->images = $this->item->images;
    $this->open_gallery = true;
  }

  public function uploadImage()
  {
    $this->validate();

    $url = Storage::put('products', $this->photo);

    $this->item->images()->create([
      'url' => $url
    ]);

    $this->photo = '';

    $this->refreshImages();
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
    $this->item = $this->item->fresh();
    $this->images = $this->item->images;
  }

  public function render()
  {
    return view('livewire.admin.gallery-images-products');
  }
}
