<?php

namespace App\Http\Livewire\Admin;

use App\Models\ImageProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GlobalGallery extends Component
{

  use WithFileUploads, WithPagination;

  public $photo, $selectedImages = [];

  public $item, $item_id, $model, $type_variant, $open_gallery = false;

  public $search = '';

  protected $listeners = ['delete'];

  protected $rules = [
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];

  public function edit()
  {
    $this->selectedImages = [];
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
    $new_gallery = array_merge(json_decode($this->item->gallery), $this->selectedImages);
    $this->item->gallery = json_encode($new_gallery);
    $this->item->save();
    $this->open_gallery = false;
  }

  public function delete(ImageProduct $image)
  {
    if (Storage::exists($image->url)) {
      Storage::delete($image->url); // ruta de la photo
    }
    $this->selectedImages = array_diff($this->selectedImages, [$image->url]);


    $model_str = [
      'Product',
      'ColorProduct',
      'ProductSize',
      'ColorProductSize',
    ];

    foreach ($model_str as $key => $value) {
      $model_name = '\\App\\Models\\' . $value;
      $product_var = $model_name::get(['id', 'gallery']);

      foreach ($product_var as $key => $value) {
        $gallery = json_decode($value->gallery);
        if (is_null($gallery))
          continue;

        $images = array_intersect($gallery, [$image->url]);

        if (count($images) > 0) {
          $value->gallery = json_encode(array_diff($gallery, [$image->url]));
          $value->save();
        }
      }
    }

    $image->delete();
    $this->refreshImages();
  }

  public function refreshImages()
  {
    $this->photo = null;
  }


  public function updatingSearch()
  {
    $this->resetPage();
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
    $this->selectedImages = json_decode($this->item->gallery) ?? [];
  }

  public function render()
  {
    $images = ImageProduct::where('url', 'like', '%' . $this->search . '%')
      ->whereNotIn('url', json_decode($this->item->gallery))
      ->orderBy('created_at', 'DESC')->paginate(16);
    return view('livewire.admin.global-gallery', compact('images'));
  }
}
