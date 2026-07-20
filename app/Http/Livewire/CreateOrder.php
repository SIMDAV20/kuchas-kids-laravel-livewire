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

    public float  $discount_amount = 0;
    public bool   $coupon_shipping_free = false;
    public string $coupon_code = '';

    // reglas de variaciones
    public $rules = [
        'contact'    => 'required',
        'phone'      => 'required|digits:9|numeric',
        'doc'        => 'required|digits:8|numeric',
        'envio_type' => 'required',
        // 'references' => 'required|min: 5',
    ];

    protected $listeners = ['couponApplied' => 'onCouponApplied', 'couponRemoved' => 'onCouponRemoved'];

    public $contact, $phone, $doc;

    public $other_contact, $other_phone, $other_doc;

    public $ruc, $social_reason, $address_invoice;

    public function onCouponApplied(array $data)
    {
        $this->discount_amount      = $data['discount_amount'] ?? 0;
        $this->coupon_shipping_free = $data['shipping_free'] ?? false;
        $this->coupon_code          = $data['coupon_code'] ?? '';
    }

    public function onCouponRemoved()
    {
        $this->discount_amount      = 0;
        $this->coupon_shipping_free = false;
        $this->coupon_code          = '';
    }

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

        foreach (Cart::content() as $item) {
            $variantId = $item->options->variant_id ?? null;
            $available = current_quantity($item->id, $variantId);

            if ($available < $item->qty) {
                $this->addError('stock', "No hay stock suficiente para \"{$item->name}\". Disponible: {$available}.");
                return;
            }
        }

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
            $freeShipping = $this->coupon_shipping_free
                || (Cart::subtotal() > $this->min_amount && $this->min_amount > 0);

            $order->shipping_cost = $freeShipping ? 0 : $this->shipping_cost;

            $order->envio = json_encode([
                'department' => Department::find($this->department_id)->name,
                'province'   => Province::find($this->province_id)->name,
                'district'   => District::find($this->district_id)->name,
                'address'    => $this->address,
                'references' => $this->references,
            ]);
        }

        $order->extra_note   = $this->extra_note;
        $order->coupon_code  = $this->coupon_code ?: null;
        $order->discount     = $this->discount_amount;
        $order->total        = max(0, $order->shipping_cost + Cart::subtotal() - $this->discount_amount);
        $order->save();

        if ($this->coupon_code) {
            \App\Models\Coupon::where('code', $this->coupon_code)->increment('used_count');
        }

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
