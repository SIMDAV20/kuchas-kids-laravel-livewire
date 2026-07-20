<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;

class CouponComponent extends Component
{
    public $coupons;

    public $categories;

    public $products;

    public $coupon; // cupón en edición

    protected $listeners = ['delete'];

    public $createForm = [
        'code' => null,
        'type' => 'category',
        'is_percentage' => true,
        'value' => null,
        'min_purchase_amount' => 0,
        'usage_limit' => null,
        'expires_at' => null,
        'selectedCategories' => [],
        'selectedProducts' => [],
    ];

    public $editForm = [
        'open' => false,
        'code' => null,
        'type' => 'category',
        'is_percentage' => true,
        'value' => null,
        'min_purchase_amount' => 0,
        'usage_limit' => null,
        'expires_at' => null,
        'is_active' => true,
        'selectedCategories' => [],
        'selectedProducts' => [],
    ];

    protected $validationAttributes = [
        'createForm.code' => 'código',
        'createForm.type' => 'tipo',
        'createForm.value' => 'valor',
        'createForm.min_purchase_amount' => 'compra mínima',
        'createForm.usage_limit' => 'límite de uso',
        'createForm.expires_at' => 'fecha de expiración',

        'editForm.code' => 'código',
        'editForm.type' => 'tipo',
        'editForm.value' => 'valor',
        'editForm.min_purchase_amount' => 'compra mínima',
        'editForm.usage_limit' => 'límite de uso',
        'editForm.expires_at' => 'fecha de expiración',
    ];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get(['id', 'name']);
        $this->products = Product::orderBy('name')->get(['id', 'name']);
        $this->getCoupons();
    }

    public function getCoupons()
    {
        $this->coupons = Coupon::latest()->get();
    }

    public function generateCode()
    {
        $this->createForm['code'] = strtoupper(Str::random(8));
    }

    public function save()
    {
        $this->validate([
            'createForm.code' => 'required|unique:coupons,code',
            'createForm.type' => 'required|in:category,product_group,shipping',
            'createForm.value' => 'required|numeric|min:0',
            'createForm.min_purchase_amount' => 'nullable|numeric|min:0',
            'createForm.usage_limit' => 'nullable|integer|min:1',
            'createForm.expires_at' => 'nullable|date|after:today',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper($this->createForm['code']),
            'type' => $this->createForm['type'],
            'is_percentage' => (bool) $this->createForm['is_percentage'],
            'value' => $this->createForm['value'],
            'min_purchase_amount' => $this->createForm['min_purchase_amount'] ?: 0,
            'usage_limit' => $this->createForm['usage_limit'] ?: null,
            'expires_at' => $this->createForm['expires_at'] ?: null,
        ]);

        if ($this->createForm['type'] === 'category') {
            $coupon->categories()->attach($this->createForm['selectedCategories']);
        } elseif ($this->createForm['type'] === 'product_group') {
            $coupon->products()->attach($this->createForm['selectedProducts']);
        }

        $this->reset('createForm');
        $this->createForm['type'] = 'category';
        $this->createForm['is_percentage'] = true;

        $this->getCoupons();
        $this->emit('saved');
    }

    public function edit(Coupon $coupon)
    {
        $this->resetValidation();
        $this->coupon = $coupon;

        $this->editForm = [
            'open' => true,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'is_percentage' => $coupon->is_percentage,
            'value' => $coupon->value,
            'min_purchase_amount' => $coupon->min_purchase_amount,
            'usage_limit' => $coupon->usage_limit,
            'expires_at' => optional($coupon->expires_at)->format('Y-m-d'),
            'is_active' => $coupon->is_active,
            'selectedCategories' => $coupon->categories->pluck('id')->toArray(),
            'selectedProducts' => $coupon->products->pluck('id')->toArray(),
        ];
    }

    public function update()
    {
        $this->validate([
            'editForm.code' => 'required|unique:coupons,code,'.$this->coupon->id,
            'editForm.type' => 'required|in:category,product_group,shipping',
            'editForm.value' => 'required|numeric|min:0',
            'editForm.min_purchase_amount' => 'nullable|numeric|min:0',
            'editForm.usage_limit' => 'nullable|integer|min:1',
            'editForm.expires_at' => 'nullable|date',
        ]);

        $this->coupon->update([
            'code' => strtoupper($this->editForm['code']),
            'type' => $this->editForm['type'],
            'is_percentage' => (bool) $this->editForm['is_percentage'],
            'value' => $this->editForm['value'],
            'min_purchase_amount' => $this->editForm['min_purchase_amount'] ?: 0,
            'usage_limit' => $this->editForm['usage_limit'] ?: null,
            'expires_at' => $this->editForm['expires_at'] ?: null,
            'is_active' => (bool) $this->editForm['is_active'],
        ]);

        $this->coupon->categories()->sync(
            $this->editForm['type'] === 'category' ? $this->editForm['selectedCategories'] : []
        );
        $this->coupon->products()->sync(
            $this->editForm['type'] === 'product_group' ? $this->editForm['selectedProducts'] : []
        );

        $this->reset('editForm');
        $this->getCoupons();
    }

    public function toggleActive(Coupon $coupon)
    {
        $coupon->is_active = ! $coupon->is_active;
        $coupon->save();
        $this->getCoupons();
    }

    public function delete($couponId)
    {
        Coupon::find($couponId)?->delete();
        $this->getCoupons();
    }

    public function render()
    {
        return view('livewire.admin.coupon-component')->layout('layouts.admin');
    }
}
