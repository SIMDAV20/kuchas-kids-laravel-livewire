<div>
  <div class="bg-white shadow-xl rounded-lg p-6 mt-4">
    @if ($type_variant == 'colors' || $type_variant == 'colors_sizes')
      {{-- Color --}}
      <div class="mb-6">
        <x-label class="text-xl mb-2">
          Color
        </x-label>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          @foreach ($colors as $color)
            <label class="flex items-center">
              <input type="radio" name="color_id" wire:model="createForm.color_id" value="{{ $color->id }}" />
              <span class="ml-2 texrt-gray-700 capitalize">
                {{ __($color->name) }}
              </span>
              <div class="w-6 h-6 rounded-full ml-2" style="background-color: {{ $color->hex }}"></div>
            </label>
          @endforeach
        </div>

        <x-input-error class="mt-2" for="color_id" />
      </div>

    @endif

    @if ($type_variant == 'sizes' || $type_variant == 'colors_sizes')

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

    @endif

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

  @if ($variants->count())
    <div class="my-12 bg-white shadow-xl rounded-lg p-6 overflow-auto">
      <table class="table-auto w-full">
        <thead>
          <tr class="text-xl">
            @if ($type_variant == 'colors' || $type_variant == 'colors_sizes')
              <th class="text-left px-4 py-2 w-1/3">
                Color
              </th>
            @endif

            @if ($type_variant == 'sizes' || $type_variant == 'colors_sizes')
              <th class="px-4 py-2 w-1/3">
                Talla
              </th>
            @endif
            <th class="px-4 py-2 w-1/3">
              Cantidad
            </th>
            <th class="px-4 py-2 w-1/3">
              Precio
            </th>
            <th class="px-4 py-2 w-1/3"></th>
            <th class="px-4 py-2 w-1/3"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($variants as $variant)
            <tr wire:key="variant-{{ $variant->id }}" class="text-center">
              @if ($type_variant == 'colors' || $type_variant == 'colors_sizes')
                <td class="px-4 py-2">
                  <div class="flex align-center">
                    <div class="w-6 h-6 rounded-full ml-2"
                      style="background-color: {{ $colors->find($variant->color_id)->hex }}"></div>
                    <div class="capitalize text-sm font-bold ml-2 text-gray-500">
                      {{ $colors->find($variant->color_id)->name }}</div>
                  </div>
                </td>
              @endif
              @if ($type_variant == 'sizes' || $type_variant == 'colors_sizes')
                <td class="px-4 py-2">
                  <div class="capitalize text-sm font-bold text-gray-500">
                    {{ $sizes->find($variant->size_id)->name }}</div>
                </td>
              @endif
              <td class="px-4 py-2">
                {{ $variant->quantity }} unid.
              </td>

              <td class="px-4 py-2">
                @php
                  $offer_price_p = @$variant->offer_price;
                @endphp

                <div class="text-sm font-bold">
                  <p class="text-gray-500">S/{{ number_format($variant->price, 2) }}</p>
                  @if ($offer_price_p > 0)
                    <p class="text-orange-500">
                      S/{{ number_format($variant->offer_price, 2) }}</p>
                  @endif
                </div>
              </td>

              <td class="px-4 py-2">
                @livewire('admin.change-status-product', ['item_id' => $variant->id, 'model' => $model_str[$type_variant]], key('variant-' . $variant->id))
              </td>

              <td class="px-4 py-2 flex">
                <x-secondary-button class="ml-auto mr-2" wire:loading.attr="disabled" wire:target="edit()"
                  wire:click="edit({{ $variant->id }})">
                  Actualizar
                </x-secondary-button>

                <div>
                  {{-- @livewire('admin.ga-images-products', ['item_id' => $variant->id, 'model' => $model_str[$type_variant]], key($variant->id)) --}}
                  @livewire('admin.global-gallery', ['item_id' => $variant->id, 'type_variant' => $type_variant], key($variant->id))
                </div>

                <x-danger-button wire:click="$emit('deleteVariant', {{ $variant->id }})">
                  Eliminar
                </x-danger-button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  {{-- MODAL EDIT --}}
  <x-dialog-modal wire:model="open">
    <x-slot name="title">
      Editar Colores
    </x-slot>

    <x-slot name="content">
      @if ($type_variant == 'colors' || $type_variant == 'colors_sizes')

        {{-- Color --}}
        <div class="mb-4">
          <x-label>
            Color
          </x-label>
          <select class="form-control w-full" wire:model="editForm.color_id">
            <option value="">Seleccione un color</option>
            @foreach ($colors as $color)
              <option value="{{ $color->id }}">{{ ucfirst(__($color->name)) }}</option>
            @endforeach
          </select>

          <x-input-error for="editForm.color_id" />

        </div>
      @endif

      @if ($type_variant == 'sizes' || $type_variant == 'colors_sizes')

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
      @endif

      <div class="my-4">
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

        {{ var_export($editForm) }}
        {{-- {{ var_export($rules) }} --}}
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button class="mr-2" wire:click="$set('open', false)">
        Cancelar
      </x-secondary-button>
      <x-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
        Actualizar
      </x-button>
    </x-slot>
  </x-dialog-modal>

</div>
