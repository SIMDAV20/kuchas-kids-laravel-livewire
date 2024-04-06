<?php

namespace App\Http\Livewire\Admin;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadBanner extends Component
{

  use WithFileUploads;

  public $photo, $image, $banners = [];

  protected $listeners = ['delete'];

  protected $rules = [
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];

  public function uploadBanner()
  {
    $this->photo = $this->image;
    $this->validateOnly('photo');

    $url = Storage::put('banners', $this->photo);

    $banner = new Banner;
    $banner->photo = $url;
    $banner->save();

    $this->photo = '';

    $this->emit('upload_banner');
    $this->reset(['image', 'photo']);
    $this->resetValidation();
    $this->mount();
  }

  public function delete(Banner $banner)
  {
    if (Storage::exists($banner->photo) && !is_null($banner->photo)) {
      Storage::delete($banner->photo);
    }
    $banner->delete();
    $this->mount();
  }

  public function mount()
  {
    $this->banners = Banner::all();
  }

  public function render()
  {
    return view('livewire.admin.upload-banner')->layout('layouts.admin');
  }
}
