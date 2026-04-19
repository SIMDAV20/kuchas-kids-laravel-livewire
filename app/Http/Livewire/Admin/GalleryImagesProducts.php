<?php

namespace App\Http\Livewire\Admin;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalleryImagesProducts extends Component
{
    use WithFileUploads;

    public $photo, $image, $item_id, $model, $open_gallery = false;
    public $blockedImages = [];

    protected $listeners = [
        'refreshGallery'     => '$refresh',
        'requestBulkDelete'  => 'bulkDeleteImages',
    ];

    protected $rules = [
        'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120'
    ];

    public function uploadImage()
    {
        $this->photo = $this->image;
        $this->validateOnly('photo');

        $name = Str::slug(pathinfo($this->photo->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . uniqid()
            . '.' . $this->photo->getClientOriginalExtension();

        $url = Storage::putFileAs('gallery', $this->photo, $name);
        Image::create(['url' => $url]);

        $this->reset(['image', 'photo']);
        $this->emit('upload_success');
    }

    public function bulkDeleteImages(array $ids)
    {
        $this->blockedImages = [];

        foreach ($ids as $id) {
            $image = Image::find($id);
            if (!$image) continue;

            $usedByProducts  = Product::where('images', 'like', '%"' . $id . '"%')->get(['id', 'name', 'slug']);
            $usedByVariants  = ProductVariant::where('images', 'like', '%"' . $id . '"%')
                ->with('product:id,name,slug')
                ->get();

            // Merge unique products from direct + variant usage
            $products = $usedByProducts->concat(
                $usedByVariants->map(fn($v) => $v->product)->filter()
            )->unique('id')->values();

            if ($products->isEmpty()) {
                if (Storage::exists($image->url)) {
                    Storage::delete($image->url);
                }
                $image->delete();
            } else {
                $this->blockedImages[] = [
                    'url'      => $image->url,
                    'products' => $products->map(fn($p) => [
                        'name' => $p->name,
                        'link' => route('admin.products.edit', $p->slug),
                    ])->all(),
                ];
            }
        }

        $this->emit('image_deleted');
    }

    public function toggleImage($imageId)
    {
        $item     = $this->getItem();
        $assigned = $item->images ?? [];

        if (in_array((string)$imageId, $assigned) || in_array((int)$imageId, $assigned)) {
            $item->images = array_values(array_filter($assigned, fn($id) => $id != $imageId));
        } else {
            $assigned[]   = (string)$imageId;
            $item->images = $assigned;
        }

        $item->save();
        $this->emit('image_toggled');
    }

    private function getItem()
    {
        $class = $this->model === 'Product' ? Product::class : ProductVariant::class;
        return $class::findOrFail($this->item_id);
    }

    public function render()
    {
        $item        = $this->getItem();
        $assignedIds = $item->images ?? [];

        return view('livewire.admin.gallery-images-products', [
            'assignedIds' => $assignedIds,
        ]);
    }
}
