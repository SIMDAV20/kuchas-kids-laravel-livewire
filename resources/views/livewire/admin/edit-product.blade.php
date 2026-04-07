<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
  <h1 class="text-3xl text-center font-semibold mb-8">
    Editar el producto "{{ $product->name }}"
  </h1>
  <div class="grid md:grid-cols-3 gap-6 mb-4">
    <div class="md:col-span-2">
      @livewire('admin.status-product', ['product' => $product], key('status-product-' . $product->id))
    </div>
    <div class="md:col-span-1">
      <div class="bg-white shadow-xl rounded-lg p-6 h-full flex justify-between items-center">
        {{-- <div class=""> --}}
        <x-danger-button wire:click="$emit('deleteProduct')" class="w-full">
          Eliminar Producto
        </x-danger-button>
        {{--
                </div> --}}
      </div>
    </div>
  </div>

  <div class="bg-white shadow-xl rounded-lg p-6 mb-4">
    <div class="grid grid-cols-2 gap-6 mb-3">
      {{-- Categoría --}}
      <div>
        <x-label value="Categorías" />
        {{-- para escuchar los cambios al seleccionar se una un wire:model --}}
        <select class="w-full form-control" wire:model="category_id">
          <option value="" selected disabled>Seleccione una categoría</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
        <x-input-error for="category_id" />
      </div>
      {{-- Subcategoria --}}
      <div>
        <x-label value="Subcategorías" />
        <select class="w-full form-control" wire:model="product.subcategory_id">
          <option value="" selected disabled>Seleccione una subcategoría</option>
          @foreach ($subcategories as $subcategory)
            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
          @endforeach
        </select>
        <x-input-error for="product.subcategory_id" />
      </div>
    </div>
    <div class="mb-4">
      <x-label value="Nombre" />
      <x-input type="text" wire:model="product.name" class="w-full" placeholder="Ingrese el nombre del producto" />
      <x-input-error for="product.name" />
    </div>
    <div class="mb-4">
      <x-label value="Slug" />
      <x-input type="text" wire:model="slug" disabled class="w-full bg-gray-200"
        placeholder="Ingrese el slug del producto" />
      <x-input-error for="slug" />
    </div>
    {{-- Descripcion --}}
    <div class="mb-4">
      <div wire:ignore>
        <x-label value="Descripción" />
        {{-- para inicializar alphine colocar x-data en el tag --}}
        <textarea class="form-control w-full" rows="4" wire:model="product.description" x-init="ClassicEditor
            .create($refs.miEditor)
            .then(function(editor) {
                editor.model.document.on('change:data', () => {
                    @this.set('product.description', editor.getData())
                })
            })
            .catch(error => {
                console.error(error);
            });" x-data
          x-ref="miEditor"></textarea>
      </div>
      <x-input-error for="product.description" />
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-3">
      {{-- Marca --}}
      @if ($brands->count() > 0)
        <div>
          <x-label value="Marca" />
          <select class="form-control w-full" wire:model="product.brand_id">
            <option value="" selected>Seleccione una marca</option>
            @foreach ($brands as $brand)
              <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
          </select>
          <x-input-error for="product.brand_id" />
        </div>
      @endif

      {{-- Video --}}
      <div>
        <x-label value="Video" />
        <x-input type="text" wire:model="product.video" class="w-full" placeholder="Ingrese el link del video" />
        <x-input-error for="product.video" />
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-4">
      @if (count($product->color_product) == 0 &&
              count($product->product_size) == 0 &&
              count($product->color_product_size) == 0)
        <div>
          <x-label value="Cantidad" />
          <x-input type="number" wire:model="product.quantity" class="w-full" />
          <x-input-error for="product.quantity" />
        </div>
      @endif

      @if (count($product->color_product) > 0 ||
              (count($product->product_size) == 0 && count($product->color_product_size) == 0))
        {{-- Precio --}}
        <div>
          <x-label value="Precio" />
          <x-input type="number" wire:model="product.price" class="w-full" step=".01" />
          <x-input-error for="product.price" />
        </div>
        {{-- Precio Oferta --}}
        <div>
          <x-label value="Precio Oferta" />
          <x-input type="number" wire:model="product.offer_price" class="w-full" step=".01" />
          <x-input-error for="product.offer_price" />
        </div>
      @endif
    </div>
    <div class="flex justify-end items-center mt-4">

      <x-action-message class="mr-3" on="saved">
        Actualizado
      </x-action-message>


      <x-button wire:loading.attr="disabled" wire:target="save" wire:click="save">
        Actualizar Producto
      </x-button>
    </div>
  </div>

  {{-- GESTIÓN DE VARIANTES DINÁMICAS --}}
  <div class="bg-white shadow-xl rounded-lg p-6 mb-4">
    <div class="flex items-center mb-6">
      <h2 class="text-xl font-semibold text-indigo-600">Gestión de Variantes</h2>
      <div class="ml-auto">
        <x-button wire:click="generateVariants" wire:loading.attr="disabled" wire:target="generateVariants">
          Generar Variantes
        </x-button>
      </div>
    </div>

    {{-- Selector de Atributos y Opciones --}}
    <div class="grid md:grid-cols-2 gap-8 mb-8 border-b pb-8">
      @foreach ($allAttributes as $attribute)
        <div>
          <h3 class="font-bold text-gray-800 mb-3 uppercase text-xs tracking-wider">{{ $attribute->name }}</h3>
          <div class="flex flex-wrap gap-2">
            @foreach ($attribute->options as $option)
              <label class="inline-flex items-center p-2 rounded-lg border cursor-pointer transition-all hover:bg-gray-50 {{ in_array($option->id, $selectedAttributes[$attribute->id] ?? []) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                <input type="checkbox" value="{{ $option->id }}" wire:model="selectedAttributes.{{ $attribute->id }}" class="hidden">
                @if ($option->hex)
                  <span class="w-4 h-4 rounded-full mr-2 border border-gray-300" style="background-color: {{ $option->hex }}"></span>
                @endif
                <span class="text-sm">{{ $option->value }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>

    {{-- Tabla de Variantes Generadas --}}
    @if ($product->variants->count() > 0)
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imágenes</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variante</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU / Slug</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($product->variants as $variant)
              <tr wire:key="variant-{{ $variant->id }}">
                <td class="px-4 py-4">
                  <div class="flex flex-wrap gap-1 mb-2 max-w-[120px]">
                    @php
                        $variantImages = is_array($variant->images) ? $variant->images : [];
                        $assignedImages = $product->images->whereIn('id', $variantImages);
                    @endphp
                    @foreach($assignedImages as $img)
                      <img src="{{ Storage::url($img->url) }}" class="w-8 h-8 object-cover rounded border">
                    @endforeach
                  </div>
                  <button wire:click="openImageModal({{ $variant->id }})" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                    <i class="fas fa-images"></i> Asignar
                  </button>
                </td>
                <td class="px-4 py-4">
                  <div class="text-sm font-bold text-gray-900">
                    {{ $variant->attributeOptions->pluck('value')->implode(' / ') }}
                  </div>
                </td>
                <td class="px-4 py-4">
                  <input type="text" value="{{ $variant->sku }}" 
                        wire:change="updateVariant({{ $variant->id }}, 'sku', $event.target.value)"
                        class="form-control text-xs w-full border-gray-200 rounded">
                </td>
                <td class="px-4 py-4">
                  <input type="number" value="{{ $variant->price }}" 
                        wire:change="updateVariant({{ $variant->id }}, 'price', $event.target.value)"
                        class="form-control text-sm w-24 border-gray-200 rounded">
                </td>
                <td class="px-4 py-4">
                  <input type="number" value="{{ $variant->stock }}" 
                        wire:change="updateVariant({{ $variant->id }}, 'stock', $event.target.value)"
                        class="form-control text-sm w-20 border-gray-200 rounded">
                </td>
                <td class="px-4 py-4 text-center">
                  <button wire:click="deleteVariant({{ $variant->id }})" 
                          onclick="confirm('¿Eliminar esta variante?') || event.stopImmediatePropagation()"
                          class="text-red-600 hover:text-red-900 mx-2">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="bg-gray-50 p-8 text-center rounded-lg border-2 border-dashed border-gray-200">
        <p class="text-gray-500 text-sm italic">No hay variantes generadas aún. Selecciona opciones arriba y haz clic en "Generar Variantes".</p>
      </div>
    @endif
  </div>

  {{-- GALERÍA DE IMÁGENES DEL PRODUCTO BASE --}}
  <div class="bg-white shadow-xl rounded-lg p-6 mb-4">
      <h2 class="text-xl font-semibold text-gray-800 mb-6">Galería del Producto</h2>
      <p class="text-sm text-gray-500 mb-4">Sube las imágenes aquí primero, luego podrás asignarlas a cada variante individualmente.</p>
      @livewire('admin.gallery-images-products', ['item_id' => $product->id, 'model' => 'Product'], key('product-' . $product->id))
  </div>

  {{-- Modal para seleccionar imágenes de variante --}}
  <x-dialog-modal wire:model="isImageModalOpen">
    <x-slot name="title">
      Seleccionar Imágenes para la Variante
    </x-slot>

    <x-slot name="content">
      @if($product->images->count() > 0)
        <div class="grid grid-cols-4 gap-4">
          @foreach($product->images as $image)
            <div 
              wire:click="toggleImageSelection({{ $image->id }})"
              class="relative cursor-pointer border-2 rounded-lg overflow-hidden transition-all {{ in_array($image->id, $selectedImageIds) ? 'border-indigo-500 shadow-md ring-2 ring-indigo-500/50' : 'border-transparent' }}">
              <img src="{{ Storage::url($image->url) }}" class="w-full h-24 object-cover">
              @if(in_array($image->id, $selectedImageIds))
                <div class="absolute top-0 right-0 bg-indigo-500 text-white p-1 rounded-bl-lg">
                  <i class="fas fa-check text-xs"></i>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <p class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg">Primero debes subir imágenes en la "Galería del Producto" abajo para poder asignarlas.</p>
      @endif
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('isImageModalOpen', false)" wire:loading.attr="disabled">
        Cancelar
      </x-secondary-button>

      <x-button class="ml-2 bg-indigo-600 hover:bg-indigo-700" wire:click="saveVariantImages" wire:loading.attr="disabled">
        Guardar Asignación
      </x-button>
    </x-slot>
  </x-dialog-modal>

  @push('scripts')
    <script>
      Livewire.on('error', mensaje => {
        Swal.fire({ icon: 'error', title: 'Oops...', text: mensaje })
      });

      Livewire.on('variantsGenerated', () => {
        const Toast = Swal.mixin({
          toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
        });
        Toast.fire({ icon: 'success', title: 'Variantes generadas correctamente' })
      });

      Livewire.on('deleteProduct', () => {
        Swal.fire({
          title: '¿Estás seguro?',
          text: "Acción irreversible",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
          if (result.isConfirmed) {
            Livewire.emitTo('admin.edit-product', 'delete');
          }
        })
      });
    </script>
  @endpush
</div>
