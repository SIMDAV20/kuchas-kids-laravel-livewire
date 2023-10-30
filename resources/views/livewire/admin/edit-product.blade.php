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

      {{-- @if (count($product->color_product) > 0 || (count($product->product_size) == 0 && count($product->color_product_size) == 0)) --}}
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
      {{-- @endif --}}
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

  <div x-data="{ type_variant: @entangle('type_variant') }" class="bg-white shadow-xl rounded-lg p-6 mb-4">

    <x-label value="¿Quieres agregar variantes a tu producto?" class="text-bold mb-3 text-lg" />
    <div class="grid md:grid-cols-3 gap-6 mb-4">
      <div class="col-span-2 flex justify-between">
        @foreach ($options as $option)
          <x-label>
            <x-input wire:model.defer="type_variant" name="type_variant" type="radio" value="{{ $option['value'] }}"
              wire:click="$emit('confirmChangeVariant', '{{ $option['value'] }}')" />
            {{ $option['label'] }}
          </x-label>
        @endforeach
      </div>
    </div>

    @if ($type_variant == 'base')
      @livewire('admin.gallery-images-products', ['item_id' => $product->id, 'model' => 'Product'], key($product->id))
    @else
      @livewire('admin.upsert-product-variant', ['product' => $product, 'type_variant' => $type_variant], key('upsert-product-variant' . $product->id))
    @endif
    {{-- @switch($type_variant)
      @case('base')
        <div>
        </div>
      @break

      @case('colors')
        @livewire('admin.color-product', ['product' => $product], key('color-product' . $product->id))
      @break

      @case('sizes')
        @livewire('admin.size-product', ['product' => $product], key('size-product' . $product->id))
      @break

      @case('colors_sizes')
        @livewire('admin.color-size-product', ['product' => $product], key('color-size-product' . $product->id))
      @break

      @default
    @endswitch --}}
  </div>

  @push('scripts')
    <script>
      Livewire.on('deleteProduct', () => {
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
            Livewire.emitTo('admin.edit-product', 'delete');

            Swal.fire(
              'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
            )
          }
        })
      })

      Dropzone.options.myAwesomeDropzone = {
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        dictDefaultMessage: "Arrastre una imagen al recuadro",
        acceptedFiles: 'image/*',
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        complete: function(file) {
          this.removeFile(file);
        },
        queuecomplete: function() {
          Livewire.emit('refreshImages')
        }
      };

      Livewire.on('deleteVariant', variantId => {
        Swal.fire({
          title: 'Esta seguro de eliminar el registro?',
          text: "Acción irreversible",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
          if (!result.isConfirmed) return;
          Livewire.emitTo('admin.upsert-product-variant', 'delete', variantId);
          Swal.fire(
            'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
          )

        })
      })

      // Livewire.on('deleteSize', sizeId => {
      //   Swal.fire({
      //     title: 'Esta seguro de eliminar el registro?',
      //     text: "Acción irreversible",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Si, eliminar!'
      //   }).then((result) => {
      //     if (result.isConfirmed) {

      //       Livewire.emitTo('admin.size-product', 'delete', sizeId);

      //       Swal.fire(
      //         'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
      //       )
      //     }
      //   })
      // })

      // Livewire.on('deleteColorProduct', pivot => {
      //   Swal.fire({
      //     title: 'Esta seguro de eliminar el registro?',
      //     text: "Acción irreversible",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Si, eliminar!'
      //   }).then((result) => {
      //     if (result.isConfirmed) {
      //       // emit es paratodos, y si uso emitTo es para un componente en especifico
      //       Livewire.emitTo('admin.color-product', 'delete', pivot);

      //       Swal.fire(
      //         'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
      //       )
      //     }
      //   })
      // })

      // Livewire.on('deleteProductSize', pivot => {
      //   Swal.fire({
      //     title: 'Esta seguro de eliminar el registro?',
      //     text: "Acción irreversible",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Si, eliminar!'
      //   }).then((result) => {
      //     if (result.isConfirmed) {
      //       // emit es paratodos, y si uso emitTo es para un componente en especifico
      //       Livewire.emitTo('admin.size-product', 'delete', pivot);

      //       Swal.fire(
      //         'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
      //       )
      //     }
      //   })
      // })

      // Livewire.on('deleteColorSize', pivot => {
      //   console.log(pivot);
      //   Swal.fire({
      //     title: 'Esta seguro de eliminar el registro?',
      //     text: "Acción irreversible",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Si, eliminar!'
      //   }).then((result) => {
      //     if (result.isConfirmed) {
      //       // emit es paratodos, y si uso emitTo es para un componente en especifico
      //       Livewire.emitTo('admin.color-size', 'delete', pivot);

      //       Swal.fire(
      //         'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
      //       )
      //     }
      //   })
      // })

      // CONFIRMAR AL MOMENTO DE CAMBIAR DE VARIANTE
      Livewire.on('confirmChangeVariant', (newValue) => {
        Swal.fire({
          title: 'Esta seguro de cambiar la variación del producto?',
          text: "Acción irreversible",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!',
          allowOutsideClick: false
        }).then((result) => {
          Livewire.emitTo('admin.edit-product', 'changeVariant', newValue, result.isConfirmed);
        })
      })
    </script>
  @endpush
</div>
