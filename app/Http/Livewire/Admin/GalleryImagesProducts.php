<?php

namespace App\Http\Livewire\Admin;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Componente de Biblioteca de Medios (Estilo WordPress)
 * Permite subir fotos a un pool global y asignarlas a modelos vía IDs JSON
 */
class GalleryImagesProducts extends Component
{
    use WithFileUploads;

    public $photo, $image, $item_id, $model, $open_gallery = false;
    public $search = '';
    
    // Filtros de biblioteca
    public $only_assigned = false;

    protected $listeners = ['refreshGallery' => '$refresh'];

    protected $rules = [
        'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
    ];

    public function uploadImage()
    {
        $this->photo = $this->image;
        $this->validateOnly('photo');

        $url = Storage::put('gallery', $this->photo);

        Image::create([
            'url' => $url
        ]);

        $this->reset(['image', 'photo']);
        $this->emit('upload_success');
    }

    public function toggleImage($imageId)
    {
        $item = $this->getItem();
        $assigned = $item->images ?? [];

        if (in_array((string)$imageId, $assigned) || in_array((int)$imageId, $assigned)) {
            $item->images = array_values(array_filter($assigned, fn($id) => $id != $imageId));
        } else {
            $assigned[] = (string)$imageId;
            $item->images = $assigned;
        }

        $item->save();
        $this->emit('image_toggled');
    }

    public function delete(Image $image)
    {
        if (Storage::exists($image->url)) {
            Storage::delete($image->url);
        }
        $image->delete();
        $this->emit('image_deleted');
    }

    private function getItem()
    {
        $class = ($this->model == 'Product') ? Product::class : ProductVariant::class;
        return $class::findOrFail($this->item_id);
    }

    public function render()
    {
        $item = $this->getItem();
        $assignedIds = $item->images ?? [];

        $query = Image::query()->orderBy('created_at', 'desc');

        if ($this->only_assigned) {
            $query->whereIn('id', $assignedIds);
        }

        $allLibraryImages = $query->get();

        return view('livewire.admin.gallery-images-products', [
            'library' => $allLibraryImages,
            'assignedIds' => $assignedIds
        ]);
    }
}
