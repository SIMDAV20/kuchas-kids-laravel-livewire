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
    'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120|dimensions:min_width=731,min_height=316,ratio=731/316'
  ];

  protected $validationAttributes = [
    'photo' => 'imagen'
  ];

  protected $messages = [
    'photo.required' => 'Debes seleccionar una imagen para el banner.',
    'photo.image'    => 'El archivo seleccionado no es una imagen válida.',
    'photo.mimes'    => 'Solo se permiten imágenes en formato JPG o PNG.',
    'photo.max'      => 'La imagen no debe pesar más de 5 MB.',
    'photo.dimensions' => 'La imagen debe medir mínimo 731×316 px y mantener la proporción 731:316.',
  ];

  public function uploadBanner()
  {
    $this->photo = $this->image;
    $this->validateOnly('photo');

    // Defensa: si el archivo temporal llegó roto (ruta vacía), evita el
    // ValueError "Path cannot be empty" y muestra un mensaje amable.
    if (!$this->photo || !$this->photo->getRealPath() || !$this->photo->isValid()) {
      $this->addError('photo', 'No se pudo procesar la imagen. Vuelve a seleccionarla e inténtalo de nuevo.');
      return;
    }

    try {
      $url = Storage::put('banners', $this->photo);
    } catch (\Throwable $e) {
      $this->addError('photo', 'Ocurrió un error al guardar la imagen. Inténtalo nuevamente.');
      return;
    }

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
