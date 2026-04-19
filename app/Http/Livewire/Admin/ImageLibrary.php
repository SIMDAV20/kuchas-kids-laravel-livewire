<?php

namespace App\Http\Livewire\Admin;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;

class ImageLibrary extends Component
{
    public $item_id;
    public $model;
    public $search       = '';
    public $perPage      = 50;
    public $hasMore      = true;
    public $onlyAssigned = false;
    public $selectedIds  = [];

    protected $listeners = ['image_toggled' => '$refresh', 'image_deleted' => '$refresh', 'upload_success' => '$refresh'];

    public function updatingSearch()
    {
        $this->perPage = 50;
        $this->hasMore = true;
    }

    public function loadMore()
    {
        $this->perPage += 20;
    }

    private function getItem()
    {
        $class = $this->model === 'Product' ? Product::class : ProductVariant::class;
        return $class::findOrFail($this->item_id);
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

    public function requestDelete($ids)
    {
        $this->emit('requestBulkDelete', $ids);
        $this->selectedIds = [];
    }

    public function render()
    {
        $item        = $this->getItem();
        $assignedIds = $item->images ?? [];

        $query = Image::query()->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where('url', 'like', '%' . $this->search . '%');
        }

        if ($this->onlyAssigned) {
            $query->whereIn('id', $assignedIds);
        }

        $total          = $query->count();
        $images         = $query->take($this->perPage)->get();
        $this->hasMore  = $images->count() < $total;

        return view('livewire.admin.image-library', [
            'images'      => $images,
            'assignedIds' => $assignedIds,
        ]);
    }
}
