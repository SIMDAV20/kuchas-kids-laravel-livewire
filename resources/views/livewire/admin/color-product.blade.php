<div>
  {{-- TODO: A BORRAR --}}
  <div class="my-12 bg-white shadow-xl rounded-lg p-6">
    {{-- Color --}}
    <div class="mb-6">
      <x-label class="text-xl mb-2">
        Color
      </x-label>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($colors as $color)
          <label class="flex items-center">
            <input type="radio" name="color_id" wire:model.defer="color_id" value="{{ $color->id }}" />
            {{-- <div class="flex"> --}}
            <span class="ml-2 texrt-gray-700 capitalize">
              {{ __($color->name) }}
            </span>
            <div class="w-6 h-6 rounded-full ml-2" style="background-color: {{ $color->hex }}"></div>
            {{--
                    </div> --}}
          </label>
        @endforeach
      </div>

      <x-input-error class="mt-2" for="color_id" />
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

      <x-button wire:loading.attr="disabled" wire:target="save" wire:click="save">
        Agregar
      </x-button>
    </div>
  </div>

  @if ($color_products->count())
    <div class="my-12 bg-white shadow-xl rounded-lg p-6 overflow-auto">
      <table class="table-auto w-full">
        <thead>
          <tr class="text-xl">
            <th class="px-4 py-2 w-1/3">
              Color
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
          @foreach ($color_products as $color_product)
            <tr wire:key="color_product-{{ $color_product->pivot->id }}" class="text-center">
              <td class="capitalize px-4 py-2">
                <div class="flex items-center justify-start">
                  <div>{{ __($colors->find($color_product->pivot->color_id)->name) }}</div>
                  <div class="w-6 h-6 rounded-full ml-2"
                    style="background-color: {{ $colors->find($color_product->pivot->color_id)->hex }}">
                  </div>
                </div>
              </td>
              <td class="px-4 py-2">
                {{ $color_product->pivot->quantity }} unid.
              </td>

              <td class="px-4 py-2">
                @php
                  $offer_price_p = @$color_product->pivot->offer_price;
                @endphp

                <div class="text-sm font-bold">
                  <p class="text-gray-500">S/{{ number_format($color_product->pivot->price, 2) }}</p>
                  @if ($offer_price_p > 0)
                    <p class="text-orange-500">
                      S/{{ number_format($color_product->pivot->offer_price, 2) }}</p>
                  @endif
                </div>
              </td>

              <td class="px-4 py-2">
                @livewire('admin.change-status-product', ['item_id' => $color_product->pivot->id, 'model' => 'ColorProduct'], key($color_product->pivot->id))
              </td>
              <td class="px-4 py-2 flex">
                <x-secondary-button class="ml-auto mr-2" wire:loading.attr="disabled" wire:target="edit()"
                  wire:click="edit({{ $color_product->pivot->id }})">
                  Actualizar
                </x-secondary-button>

                <div>
                  @livewire('admin.gallery-images-products', ['item_id' => $color_product->pivot->id, 'model' => 'ColorProduct'], key($color_product->pivot->id))
                </div>

                {{-- <div>
                                    @livewire('admin.gallery-images-products', ['item_id' => $color_product->pivot->id, 'model' => 'ColorProduct'], key($color_product->pivot->id))
                                </div> --}}
                {{--
                                <x-danger-button wire:key="delete-color_product"
                                    wire:click="$emit('deleteColorProduct', {{ $color_product->pivot->id }})">
            Eliminar
            </x-danger-button> --}}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <x-dialog-modal wire:model="editForm.open">
    <x-slot name="title">
      Editar Colores
    </x-slot>

    <x-slot name="content">
      {{-- Color --}}
      <div class="mb-4">
        <x-label>
          Color
        </x-label>
        <select wire:model="editForm.color_id" class="form-control w-full">
          <option value="">Seleccione un color</option>
          @foreach ($colors as $color)
            <option value="{{ $color->id }}">{{ ucfirst(__($color->name)) }}</option>
          @endforeach
        </select>
      </div>
      {{-- Cantidad --}}
      <div class="mb-4">
        <x-label>
          Cantidad
        </x-label>
        <x-input wire:model="editForm.quantity" class="w-full" type="number" placeholder="Ingrese una cantidad" />
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button class="mr-2" wire:click="$set('editForm.open', false)">
        Cancelar
      </x-secondary-button>
      <x-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
        Actualizar
      </x-button>
    </x-slot>
  </x-dialog-modal>
</div>
