<div>
  {{-- TODO: A BORRAR --}}
  <div class="bg-white shadow-xl rounded-lg p-6 mt-12">
    {{-- Talla --}}
    <div class="mb-6">
      <x-label class="text-xl mb-2">
        Talla
      </x-label>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($sizes as $size)
        <label class="flex items-center">
          <input type="radio" wire:model="createForm.size_id" value="{{ $size->id }}" />
          <span class="ml-2 texrt-gray-700 capitalize">
            {{ $size->name }}
          </span>
        </label>
        @endforeach
      </div>

      <x-input-error class="mt-2" for="createForm.size_id" />
    </div>

    <div class="mb-4">
      <x-label class="text-xl mb-2">
        Slug
      </x-label>
      <p>{{ $createForm['slug'] }}</p>
      <x-input-error class="mt-2" for="createForm.slug" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {{-- Cantidad --}}
      <div>
        <x-label value="Cantidad" />
        <x-input type="number" wire:model="createForm.quantity" class="w-full" step="1" />
        <x-input-error for="createForm.quantity" />
      </div>

      {{-- Precio --}}
      <div>
        <x-label value="Precio" />
        <x-input type="number" wire:model="createForm.price" class="w-full" step=".01" />
        <x-input-error for="createForm.price" />
      </div>

      {{-- Precio Oferta --}}
      <div>
        <x-label value="Precio Oferta" />
        <x-input type="number" wire:model="createForm.offer_price" class="w-full" step=".01" />
        <x-input-error for="createForm.offer_price" />
      </div>
    </div>


    <div class="flex justify-end items-center mt-4">
      <x-action-message class="mr-3" on="saved">
        Agregado
      </x-action-message>
      <x-button wire:click="save" wire:loading.attr="disabled" wire:target="save">
        Agregar
      </x-button>
    </div>
  </div>

  @if ($product_sizes->count())
  <div class="my-12 bg-white shadow-xl rounded-lg p-6 overflow-auto">
    <table class="table-auto w-full">
      <thead>
        <tr class="text-xl">
          <th class="px-4 py-2 w-1/3">
            Talla
          </th>
          <th class="px-4 py-2 w-1/3">
            Cantidad
          </th>
          <th class="px-4 py-2 w-1/3">
            Precio / Oferta
          </th>
          <th class="px-4 py-2 w-1/3"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($product_sizes as $product_size)
        <tr wire:key="product_size-{{ $product_size->pivot->id }}" class="text-center">
          <td class="px-4 py-2">
            <div class="capitalize text-sm font-bold text-gray-500">
              {{ $sizes->find($product_size->pivot->size_id)->name }}</div>
          </td>
          <td class="px-4 py-2">
            {{ $product_size->pivot->quantity }} unid.
          </td>

          <td class="px-4 py-2">
            @php
            $offer_price_p = @$product_size->pivot->offer_price;
            @endphp

            <div class="text-sm font-bold">
              <p class="text-gray-500">S/{{ number_format($product_size->pivot->price, 2) }}</p>
              @if ($offer_price_p > 0)
              <p class="text-orange-500">
                S/{{ number_format($product_size->pivot->offer_price, 2) }}</p>
              @endif
            </div>
          </td>

          <td class="px-4 py-2 flex">
            <x-secondary-button class="ml-auto mr-2" wire:loading.attr="disabled" wire:target="edit()" wire:click="edit({{ $product_size->pivot->id }})">
              Actualizar
            </x-secondary-button>

            <div>
              @livewire('admin.gallery-images-products', ['item_id' => $product_size->pivot->id, 'model' => 'ProductSize'], key($product_size->pivot->id))
            </div>

            <x-danger-button wire:click="$emit('deleteProductSize', {{ $product_size->pivot->id }})">
              Eliminar
            </x-danger-button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  {{-- @livewire('admin.color-size', ['size' => $size], key('color-size-'. $size->id)) --}}

  <x-dialog-modal wire:model="open">
    <x-slot name="title">
      Editar Talla
    </x-slot>

    <x-slot name="content">
      {{-- Talla --}}
      <div class="mb-6">
        <x-label class="text-xl mb-2">
          Talla
        </x-label>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          @foreach ($sizes as $size)
          <label class="flex items-center">
            <input type="radio" wire:model="editForm.size_id" value="{{ $size->id }}" />
            <span class="ml-2 texrt-gray-700 capitalize">
              {{ $size->name }}
            </span>
          </label>
          @endforeach
        </div>

        <x-input-error class="mt-2" for="editForm.size_id" />

      </div>

      <div class="mb-4">
        <x-label class="text-xl mb-2">
          Slug
        </x-label>
        <p>{{ $editForm['slug'] }}</p>
        <x-input-error class="mt-2" for="editForm.slug" />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Cantidad --}}
        <div>
          <x-label value="Cantidad" />
          <x-input type="number" wire:model="editForm.quantity" class="w-full" step="1" />
          <x-input-error for="editForm.quantity" />
        </div>

        {{-- Precio --}}
        <div>
          <x-label value="Precio" />
          <x-input type="number" wire:model="editForm.price" class="w-full" step=".01" />
          <x-input-error for="editForm.price" />
        </div>

        {{-- Precio Oferta --}}
        <div>
          <x-label value="Precio Oferta" />
          <x-input type="number" wire:model="editForm.offer_price" class="w-full" step=".01" />
          <x-input-error for="editForm.offer_price" />
        </div>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('open', false)">
        Cancelar
      </x-secondary-button>
      <x-button wire:click="update()" wire:loading.attr="disabled" wire:target="update">
        Actualizar
      </x-button>
    </x-slot>
  </x-dialog-modal>

</div>
