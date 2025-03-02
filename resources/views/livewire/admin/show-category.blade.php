<div class="container py-12">
  <x-form-section submit="save" class="mb-6">
    <x-slot name="title">
      Crear Nueva Subcategoría
    </x-slot>
    <x-slot name="description">
      Complete la información necesario para poder crear una nueva subcategoría
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
      <div class="col-span-6">
        <x-label>
          Palabras Claves (SEO)

          <small class="text-gray-500 float-end mr-2">Enter para agregar</small>
        </x-label>
        <x-input wire:model="createForm.inputKeyword" wire:keydown.enter.prevent="addKeyword('create')" type="text"
          class="w-full mt-1" />

        @if ($createForm['keywords'])
          <div class="w-wull border  border-gray-400 mt-2 p-2 rounded">
            @foreach ($createForm['keywords'] as $item)
              <div
                class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 rounded-full bg-violet-500 text-white border">
                {{ $item }}
                <i class="fa fa-times text-white cursor-pointer ml-2"
                  wire:click="deleteKeyword('create', '{{ $item }}')"></i>
              </div>
            @endforeach
          </div>
        @endif

        <x-input-error for="createForm.keywords" />
      </div>
    </x-slot>

    <x-slot name="actions">
      <x-action-message class="mr-3" on="saved">
        Subcategoría creada
      </x-action-message>
      <x-button>
        Agregar
      </x-button>
    </x-slot>
  </x-form-section>

  <x-action-section class="mb-5">
    <x-slot name="title">
      Lista de Subcategorías
    </x-slot>
    <x-slot name="description">
      Aqui encontrará todas las subcategorías agregadas
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
            @foreach ($subcategories as $subcategory)
              <tr wire:key="subcategory-{{ $subcategory->id }}">
                <td class="px-6 py-4">
                  <span class="uppercase">
                    {{ $subcategory->name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  {{ $subcategory->products_count }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @livewire('admin.change-status-entity', ['entity' => $subcategory], key($subcategory->id))
                </td>
                <td class="px-6 py-4">
                  <div class="flex justify-center divide-x divide-gray-300 font-semibold">
                    <a wire:click="edit('{{ $subcategory->id }}')" class="pr-2 hover:text-blue-600 cursor-pointer">
                      Editar
                    </a>
                    {{-- tiene q estar entre comillas el $subcategory->id sino no se va enviar como cadena --}}
                    <a wire:click="$emit('deleteSubcategory', '{{ $subcategory->id }}')"
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

  {{-- Sort subcategories by position --}}
  <x-action-section class="mb-5">
    <x-slot name="title">
      Ordenar las Subcategorías
    </x-slot>
    <x-slot name="description">
      Aqui podrás ordenar todas las subcategorías
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
          <tbody wire:sortable="updateSubCategoriesPosition()" class="divide-y divide-gray-300">
            @foreach ($subcategories as $subcategory)
              <tr wire:sortable.item="{{ $subcategory->id }}" wire:key="subcategory-order-{{ $subcategory->id }}">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <i class="fas fa-allergies cursor-move mr-2"></i>
                    <span class="uppercase hover:text-blue-600">
                      {{ $subcategory->name }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  {{ $subcategory->position }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <x-action-message class="mr-3 mt-2 text-blue-600" on="updated_subcategories_positions">
          Posiciones Actualizadas
        </x-action-message>
      </div>
    </x-slot>
  </x-action-section>

  {{-- Sort product by subcategory --}}
  <x-action-section>
    <x-slot name="title">
      Ordenar productos por subcategoría
    </x-slot>
    <x-slot name="description">
      Aqui podrá ordenar los productos por subcategoría
    </x-slot>
    <x-slot name="content">
      {{-- Subcategoria --}}
      <div>
        <x-label value="Subcategorías" />
        <select class="w-full form-control" wire:model="subcategory_id">
          <option value="" selected disabled>Seleccione una subcategoría</option>
          @foreach ($subcategories as $subcategory)
            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
          @endforeach
        </select>
      </div>


      @if ($subcategory_id !== '')
        <hr class="mt-6">
        <table class="text-gray-600 w-full">
          <thead class="border-b border-gray-300s">
            <tr class="text-left">
              <th class="py-2">Nombre del producto</th>
            </tr>
            <tr class="text-left">
              <th class="py-2">Orden</th>
            </tr>
          </thead>
          <tbody wire:sortable="updateProductsPosition()" class="divide-y divide-gray-300">
            @foreach ($subcategory_products as $product)
              <tr wire:sortable.item="{{ $product->id }}" wire:key="product-{{ $product->id }}">
                <td class="py-2">

                  <div class="flex items-center">
                    <i class="fas fa-allergies cursor-pointer"></i>
                    <span class="uppercase hover:text-blue-600 mx-2">
                      {{ $product->name }}
                    </span>
                  </div>
                </td>
                <td class="py-2">
                  {{ $product->position }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
      <x-action-message class="mr-3 mt-2 text-blue-600" on="updated_products_positions">
        Posiciones Actualizadas
      </x-action-message>
    </x-slot>
  </x-action-section>

  {{-- Modal --}}
  <x-dialog-modal wire:model="editForm.open">
    <x-slot name="title">
      Editar Subategoría
    </x-slot>

    <x-slot name="content">
      <div class="space-y-3">

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
            Palabras Claves (SEO)

            <small class="text-gray-500 float-end mr-2">Enter para agregar</small>
          </x-label>
          <x-input wire:model="editForm.inputKeyword" wire:keydown.enter.prevent="addKeyword('edit')" type="text"
            class="w-full mt-1" />

          @if ($editForm['keywords'])
            <div class="w-wull border  border-gray-400 mt-2 p-2 rounded">
              @foreach ($editForm['keywords'] as $item)
                <div
                  class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 rounded-full bg-violet-500 text-white border">
                  {{ $item }}
                  <i class="fa fa-times text-white cursor-pointer ml-2"
                    wire:click="deleteKeyword('edit', '{{ $item }}')"></i>
                </div>
              @endforeach
            </div>
          @endif

          <x-input-error for="editForm.keywords" />
        </div>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('editForm.open', false)">
        Cancelar
      </x-secondary-button>
      <x-button class="ml-2" wire:click="update" wire:loading.attr="disabled" wire:target="editImage, update">
        Actualizar
      </x-button>
    </x-slot>
  </x-dialog-modal>

  @push('scripts')
    <script>
      Livewire.on('deleteSubcategory', subcategoryId => {
        Swal.fire({
          title: 'Esta seguro de eliminar el registro?',
          text: "Acción irreversible",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
          if (result.isConfirmed) {
            Livewire.emitTo('admin.show-category', 'delete', subcategoryId);
            Swal.fire(
              'Eliminado!',
              'El resgistro ha sido eliminado.',
              'success'
            )
          }
        })
      })
    </script>
  @endpush
</div>
