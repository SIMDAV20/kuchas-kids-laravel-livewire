<div>
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

    {{-- Cantidad --}}
    <div class="mb-6">
      <x-label class="text-xl mb-2">
        Cantidad
      </x-label>

      <x-input type="number" wire:model.defer="quantity" placeholder="Ingrese una cantidad" class="w-full" />
      <x-input-error for="quantity" />
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

  @if ($product_colors->count())
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
              Estado
            </th>
            <th class="px-4 py-2 w-1/3">
              Acciones
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($product_colors as $product_color)
            <tr wire:key="product_color-{{ $product_color->pivot->id }}" class="text-center">
              <td class="capitalize px-4 py-2">
                <div class="flex items-center justify-start">
                  <div>{{ __($colors->find($product_color->pivot->color_id)->name) }}</div>
                  <div class="w-6 h-6 rounded-full ml-2"
                    style="background-color: {{ $colors->find($product_color->pivot->color_id)->hex }}">
                  </div>
                </div>
              </td>
              <td class="px-4 py-2">
                {{ $product_color->pivot->quantity }} unid.
              </td>
              <td class="px-4 py-2">
                @livewire('admin.change-status-product', ['item_id' => $product_color->pivot->id, 'model' => 'ColorProduct'], key($product_color->pivot->id))
              </td>
              <td class="px-4 py-2 flex">
                <x-secondary-button wire:key="edit-product_color" class="ml-auto mr-2" wire:loading.attr="disabled"
                  wire:target="edit()" wire:click="edit({{ $product_color->pivot->id }})">
                  Actualizar
                </x-secondary-button>

                <div>
                  @livewire('admin.gallery-images-products', ['item_id' => $product_color->pivot->id, 'model' => 'ColorProduct'], key($product_color->pivot->id))
                </div>

                <x-danger-button wire:key="delete-product_color"
                  wire:click="$emit('deleteColorProduct', {{ $product_color->pivot->id }})">
                  Eliminar
                </x-danger-button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <x-dialog-modal wire:model="open">
    <x-slot name="title">
      Editar Colores
    </x-slot>

    <x-slot name="content">
      {{-- Color --}}
      <div class="mb-4">
        <x-label>
          Color
        </x-label>
        <select class="form-control w-full" wire:model="pivot_color_id">
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
        <x-input wire:model="pivot_quantity" class="w-full" type="number" placeholder="Ingrese una cantidad" />
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('open', false)">
        Cancelar
      </x-secondary-button>
      <x-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
        Actualizar
      </x-button>
    </x-slot>
  </x-dialog-modal>
</div>
