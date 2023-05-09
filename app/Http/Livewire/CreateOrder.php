<?php

namespace App\Http\Livewire;

use App\Models\Province;
use App\Models\Zone;
use App\Models\Order;
use Livewire\Component;
use App\Models\District;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;

class CreateOrder extends Component
{
    public $envio_type = 2;
    public $other_person = false;
    public $facturacion = false;
    public $min_amount = 0;

    public $departments = [], $provinces = [], $districts = [];
    // provinces es provinces

    public $department_id = '', $province_id = '', $district_id = '',
        $address, $references, $shipping_cost = 0, $extra_note;

    // reglas de variaciones
    public $rules = [
        'contact'    => 'required',
        'phone'      => 'required|digits:9|numeric',
        'doc'        => 'required|digits:8|numeric',
        'envio_type' => 'required',
        // 'references' => 'required|min: 5',
    ];

    public $contact, $phone, $doc;

    public $other_contact, $other_phone, $other_doc;

    public $ruc, $social_reason, $address_invoice;

    public function updatingOtherPerson($value)
    {
        if ($value) {
            $this->resetValidation(['other_contact', 'other_phone', 'other_doc']);
            $this->reset(['other_contact', 'other_phone', 'other_doc']);
        }
    }

    public function updatingEnvioType($value)
    {
        if ($value == 1 || $value == 3) {
            $this->resetValidation([
                'department_id', 'province_id', 'district_id', 'address', 'references'
            ]);

            $this->reset(['shipping_cost', 'department_id', 'province_id', 'district_id', 'address', 'references']);
        }
    }

    public function updatingDepartmentId($value)
    {
        $this->shipping_cost = 0;
        if (strlen($value) == 1)
            $value = '0' . $value;

        $this->provinces = Province::where('department_id', $value)->orderBy('name')->get();
        $this->reset('province_id');
        $this->reset('district_id');
    }

    public function updatingProvinceId($value)
    {
        $this->shipping_cost = 0;
        if (strlen($value) == 3)
            $value = '0' . $value;

        $this->districts = District::where('province_id', $value)
            // ->where('zone_id', '>', 0)
            ->orderBy('name')
            ->get();
        $this->reset('district_id');
    }

    public function updatingDistrictId($value)
    {
        $district = District::find($value);
        $zone = Zone::find($district->zone_id);
        $this->shipping_cost = $zone ? $zone->cost : 0;
    }

    public function create_order()
    {
        $rules = $this->rules;

        if ($this->other_person) {
            $rules['other_contact'] = 'required';
            $rules['other_phone']   = 'required|digits:9|numeric';
            $rules['other_doc']     = 'required|digits:8|numeric';
        }

        if ($this->envio_type == 2) {
            $rules['department_id'] = 'required';
            $rules['province_id']       = 'required';
            $rules['district_id']   = 'required';
            $rules['address']       = 'required';
            $rules['references']    = 'required';
        }

        if ($this->facturacion) {
            $rules['ruc']             = 'required|digits:11|numeric';
            $rules['social_reason']   = 'required|min:3|max:60';
            $rules['address_invoice'] = 'required';
        }

        $this->validate($rules);

        $order = new Order();

        $order->user_id       = auth()->user()->id;
        $order->contact       = $this->contact;
        $order->phone         = $this->phone;
        $order->doc_number    = $this->doc;
        $order->envio_type    = $this->envio_type;
        $order->shipping_cost = 0;
        $order->content       = Cart::content();

        if ($this->other_person) {
            $order->other_contact    = $this->other_contact;
            $order->other_phone      = $this->other_phone;
            $order->other_doc_number = $this->other_doc;
        }

        if ($this->envio_type == 2) {
            if (Cart::subtotal() > $this->min_amount && $this->min_amount > 0) {
                $order->shipping_cost = 0;
            } else {
                $order->shipping_cost = $this->shipping_cost;
            }

            $order->envio = json_encode([
                'department' => Department::find($this->department_id)->name,
                'province'   => Province::find($this->province_id)->name,
                'district'   => District::find($this->district_id)->name,
                'address'    => $this->address,
                'references' => $this->references,
            ]);
        }

        $order->extra_note = $this->extra_note;
        $order->total      = $order->shipping_cost + Cart::subtotal();
        $order->save();

        $user = User::find(auth()->user()->id);
        $user->phone       = $this->phone;
        $user->doc_number  = $this->doc;
        $user->save();

        if ($this->facturacion) {
            $invoice = new Invoice();
            $invoice->ruc              = $this->ruc;
            $invoice->company_name     = $this->social_reason;
            $invoice->company_address  = $this->address_invoice;
            $invoice->order_id         = $order->id;
            $invoice->save();
        }

        foreach (Cart::content() as $item) {
            discount($item);
        }

        // si acepta todos los términos antes de pagar se limpia el carrito
        Cart::destroy();

        return redirect()->route('orders.payment', $order);
    }

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->contact     = auth()->user()->name;
        $this->phone       = auth()->user()->phone;
        $this->doc         = auth()->user()->doc_number;

        $this->min_amount = Setting::first()->min_amount;
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}
