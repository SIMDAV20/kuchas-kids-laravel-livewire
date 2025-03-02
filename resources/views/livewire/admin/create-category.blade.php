<div>
  <x-form-section submit="save" class="mb-6">
    <x-slot name="title">
      Crear Nueva Categoría
    </x-slot>
    <x-slot name="description">
      Complete la información necesario para poder crear una nueva categoría
    </x-slot>
    {{-- el form tiene un grid de 6 columnas --}}
    <x-slot name="form">
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Nombre
        </x-label>
        <x-input wire:model="createForm.name" type="text" class="w-full mt-1" />

        <x-input-error for="createForm.name" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Slug
        </x-label>
        <x-input wire:model="createForm.slug" type="text" class="w-full bg-gray-200 mt-1" disabled />

        <x-input-error for="createForm.slug" />
      </div>
      {{-- <div class="col-span-6 sm:col-span-4">
                <x-label class="flex justify-between items-center">
                    Ícono

                    <a class="text-blue-400" href="https://fontawesome.com/v5.15/icons?d=gallery&p=2&m=free"
                        target="_blank">FontAwesome</a>
                </x-label>
                <x-input wire:model.defer="createForm.icon" type="text" class="w-full mt-1" />

                <x-input-error for="createForm.icon" />
            </div> --}}
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Marcas
        </x-label>
        <div class="grid grid-cols-4">
          @foreach ($brands as $brand)
            <x-label>
              <x-checkbox wire:model.defer="createForm.brands" name="brands[]" value="{{ $brand->id }}" />
              {{ $brand->name }}
            </x-label>
          @endforeach
        </div>

        <x-input-error for="createForm.brands" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Imagen
        </x-label>
        <x-input wire:model="createForm.image" accept="image/*" type="file" class="w-full mt-1"
          id="{{ $rand }}" />

        <x-input-error for="createForm.image" />
      </div>
    </x-slot>
    <x-slot name="actions">
      <x-action-message class="mr-3" on="saved">
        Categoría creada
      </x-action-message>
      <x-button>
        Agregar
      </x-button>
    </x-slot>
  </x-form-section>

  <x-action-section class="mb-5">
    <x-slot name="title">
      Lista de Categorías
    </x-slot>
    <x-slot name="description">
      Aqui encontrará todas las categorías agregadas
    </x-slot>
    <x-slot name="content">
      <div class="overflow-x-auto">
        <table class="text-gray-600 min-w-full divide-y">
          <thead class="border-b border-gray-300s">
            <tr class="text-left">
              <th class="px-6 py-4">Nombre</th>
              <th class="px-6 py-4 text-center">Cant. Prods.</th>
              <th class="px-6 py-4">Estado</th>
              <th class="px-6 py-4 text-center">Acción</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-300">
            @foreach ($categories as $category)
              <tr wire:key="category-{{ $category->id }}">
                <td class="px-6 py-4">
                  <a href="{{ route('admin.categories.show', $category) }}" class="uppercase hover:text-blue-600">
                    {{ $category->name }}
                  </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  {{ $category->products_count }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @livewire('admin.change-status-entity', ['entity' => $category], key($category->id))
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex justify-center divide-x divide-gray-300 font-semibold">
                    <a wire:click="edit('{{ $category->slug }}')" class="pr-2 hover:text-blue-600 cursor-pointer">
                      Editar
                    </a>
                    {{-- tiene q estar entre comillas el $category->slug sino no se va enviar como cadena --}}
                    <a wire:click="$emit('deleteCategory', '{{ $category->slug }}')"
                      class="pl-2 hover:text-red-600 cursor-pointer">
                      Eliminar
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </x-slot>
  </x-action-section>

  {{-- Sort categories by position --}}
  <x-action-section>
    <x-slot name="title">
      Ordenar las Categorías
    </x-slot>
    <x-slot name="description">
      Aqui podrás ordenar todas las categorías
    </x-slot>
    <x-slot name="content">
      <div class="overflow-x-auto">
        <table class="text-gray-600 min-w-full divide-y">
          <thead class="border-b border-gray-300s">
            <tr class="text-left">
              <th class="px-6 py-4">Nombre</th>
              <th class="px-6 py-4 text-center">Orden</th>
            </tr>
          </thead>
          <tbody wire:sortable="updateCategoriesPosition()" class="divide-y divide-gray-300">
            @foreach ($categories as $category)
              <tr wire:sortable.item="{{ $category->id }}" wire:key="category-order-{{ $category->id }}">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <i class="fas fa-allergies cursor-pointer mr-2"></i>
                    <p class="uppercase hover:text-blue-600">
                      {{ $category->name }}
                    </p>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  {{ $category->position }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <x-action-message class="mr-3 mt-2 text-blue-600" on="updated_positions">
          Posiciones Actualizadas
        </x-action-message>
      </div>
    </x-slot>
  </x-action-section>

  {{-- Modal --}}
  <x-dialog-modal wire:model="editForm.open">
    <x-slot name="title">
      Editar Categoría
    </x-slot>

    <x-slot name="content">
      <div class="space-y-3">

        <div>
          @if ($editImage)
            <img class="w-full h-64 object-cover object-center" src="{{ $editImage->temporaryUrl() }}" alt="">
          @else
            <img class="w-full h-64 object-cover object-center" src="{{ @Storage::url($editForm['image']) }}"
              alt="">
          @endif
        </div>

        <div>
          <x-label>
            Nombre
          </x-label>
          <x-input wire:model="editForm.name" type="text" class="w-full mt-1" />

          <x-input-error for="editForm.name" />
        </div>
        <div>
          <x-label>
            Slug
          </x-label>
          <x-input wire:model="editForm.slug" type="text" class="w-full bg-gray-200 mt-1" disabled />

          <x-input-error for="editForm.slug" />
        </div>
        <div>
          <x-label>
            Marcas
          </x-label>
          <div class="grid grid-cols-4">
            @foreach ($brands as $brand)
              <x-label>
                <x-checkbox wire:model.defer="editForm.brands" name="brands[]" value="{{ $brand->id }}" />
                {{ $brand->name }}
              </x-label>
            @endforeach
          </div>

          <x-input-error for="editForm.brands" />
        </div>
        <div>
          <x-label>
            Imagen
          </x-label>
          <x-input wire:model="editImage" accept="image/*" type="file" class="w-full mt-1" />

          <x-input-error for="editImage" />
        </div>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('editForm.open', false)">
        Cancelar
      </x-secondary-button>
      <x-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="editImage, update">
        Actualizar
      </x-danger-button>
    </x-slot>
  </x-dialog-modal>
</div>
