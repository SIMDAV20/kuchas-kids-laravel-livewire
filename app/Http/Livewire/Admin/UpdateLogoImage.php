<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateLogoImage extends Component
{
  use WithFileUploads;

  public $photo = null, $current_logo, $image, $record;
  // the url is saved in logo's column in settings's table

  protected $rules = [
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];


  public function updateLogo()
  {
    $this->photo = $this->image;
    $this->validateOnly('photo');

    if (!is_null($this->current_logo)) {
      if (Storage::exists($this->current_logo)) Storage::delete($this->current_logo);
    }

    $url = Storage::put('settings', $this->photo);

    $this->record->update([
      'logo' => $url
    ]);

    $this->photo = '';

    $this->emit('upload_logo');
    $this->reset(['image', 'photo']);
    $this->resetValidation();
    $this->mount();
  }

  public function mount()
  {
    $this->record = Setting::first();
    $this->current_logo = $this->record->logo;
  }

  public function render()
  {
    return view('livewire.admin.update-logo-image');
  }
}
