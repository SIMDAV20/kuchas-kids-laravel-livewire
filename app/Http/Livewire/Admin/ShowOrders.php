<?php

namespace App\Http\Livewire\Admin;

use App\Mail\MessageRecieved;
use App\Models\Image;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ShowOrders extends Component
{
    use WithPagination, WithFileUploads;

    public $search, $status, $rand, $order;

    public $editImage = null;
    public $editForm = [
        'open' => false,
        'id' => -1,
        'image' => null,
    ];

    protected $rules = [];


    protected $validationAttributes = [
        'editImage' => 'imagen',
    ];

    public function editImages(Order $order)
    {
        $this->reset(['editImage']);

        $this->resetValidation();

        $this->order = $order;

        $this->editForm['open']   = true;
        $this->editForm['id']     = $order->id;
        $this->editForm['image']  = @$order->images->url;
    }

    public function updateImages()
    {
        $this->validate([
            'editImage' => 'required|mimes:pdf,png,jpg,jpeg,xls,xlsx,png|max:2048' //2MB
        ]);

        Storage::delete($this->editForm['image']);

        $image_delete = Image::where('url', $this->editForm['image']);
        if (isset($image_delete)) {
            $image_delete->delete();
        }

        $image = $this->editImage;
        $name = explode('.', $image->getClientOriginalName());
        $extension = $image->extension();
        $name = Str::slug($name[0]) . '_' . time() . '.' . $extension;
        $path = $image->storeAs('invoices', $name);
        // $image = $this->editImage->store('invoices');
        $new_image = Image::create([
            'url' => $path,
            'imageable_id'   => $this->order->id,
            'imageable_type' => Order::class
        ]);

        $data = ['file' => Storage::url($new_image->url)];

        Mail::to($this->order->user->email)
            ->queue(new MessageRecieved(
                $data,
                'Kuchas-kids Comprobante de Pago'
            ));


        $this->cleanupOldUploads();

        $this->reset(['editForm', 'editImage']);

        $this->render();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->rand = rand();
    }

    public function render()
    {
        $s = $this->search;
        $orders = Order::orderId($s)
            // ->shippingCost($s)
            // ->total($s)
            ->contact($s)
            // ->phone($s)
            // ->docnumber($s)
            ->otherContact($s)
            // ->otherPhone($s)
            // ->otherDocnumber($s)
            // ->extraNote($s)
            ->paymentMethod($s)
            ->orderBy('id', 'desc');

        if ($this->status > 0) {
            $orders = $orders->where('status', $this->status);
        }

        $orders = $orders->paginate(10);

        foreach ($orders as $key => $order) {
            $arrays[] = [];
            $items = json_decode($order->content);

            $count_prods = 0;
            foreach ($items as $key => $item) {
                $item = (array)$item;
                $count_prods += $item['qty'];
            }
            $order->qty_prods = $count_prods;
        }

        return view('livewire.admin.show-orders', compact('orders'));
    }

    protected function cleanupOldUploads()
    {

        $storage = Storage::disk('local');

        foreach ($storage->allFiles('livewire-tmp') as $filePathname) {
            if (!$storage->exists($filePathname)) continue;
            $yesterdaysStamp = now()->subSeconds(10)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }
}
