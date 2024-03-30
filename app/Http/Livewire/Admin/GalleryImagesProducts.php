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

  public $photo, $images = [], $image, $item_id, $item, $model, $open_gallery = false;

  protected $listeners = ['delete'];

  protected $rules = [
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];

  public function edit()
  {
    $this->open_gallery = true;
  }

  public function uploadImage()
  {
    $this->photo = $this->image;
    $this->validateOnly('photo');

    $url = Storage::put('products', $this->photo);

    $this->item->images()->create([
      'url' => $url
    ]);

    $this->photo = '';

    $this->emit('upload_image');
    $this->reloadImages();
    $this->reset(['image', 'photo']);
    $this->resetValidation();
  }

  public function delete(Image $image)
  {
    if (Storage::exists($image->url)) {
      Storage::delete($image->url); // ruta de la photo
    }
    $image->delete();
    $this->reloadImages();
  }

  public function reloadImages()
  {
    $this->item = findProduct($this->model, $this->item_id);
    $this->images = $this->item->images;
  }

  public function mount()
  {
    $this->reloadImages();
  }

  public function render()
  {
    return view('livewire.admin.gallery-images-products');
  }
}
