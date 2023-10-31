<div>
  <x-slot name="header">
    <div class="flex items-center">
      <h2 class="font-semibold text-xl text-gray-600">
        Lista de Productos
      </h2>

      <x-button-enlace href="{{ route('admin.products.create') }}" class="ml-auto">
        Agregar Producto
      </x-button-enlace>
    </div>
  </x-slot>

  <div class="container py-12">

    <x-table-responsive>

      <div class="px-6 py-4">

        <x-input type="text" wire:model="search" placeholder="Ingrese el nombre del producto que quiere buscar"
          class="w-full" />

      </div>

      @if ($products->count())
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nombre
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categoría / Subcategoría
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Precio
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Oferta
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Stock
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estado
              </th>
              <th scope="col"
                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($products as $product)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">

                      @if (!is_null($product->gallery))
                        {{-- {{ var_export($product->gallery) }} --}}
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ @Storage::url($product->gallery[0]) }}"
                          alt="{{ $product->name }}">
                      @endif
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ $product->name }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  {{-- whitespace-nowrap --}}
                  <div class="text-sm text-gray-900 w-48">
                    {{ $product->subcategory->category->name }}
                    <br>
                    {{ $product->subcategory->name }}
                  </div>
                </td>

                {{-- Precio --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{-- si es precio por color y talla --}}
                  @if (count($product->color_product_size))
                    @foreach ($product->color_product_size as $color_prod_size)
                      <p class="font-bold">S/{{ $color_prod_size->price }}</p>
                    @endforeach
                    {{-- si es precio por solo talla --}}
                  @elseif (count($product->product_size))
                    @foreach ($product->product_size as $prod_size)
                      <p class="font-bold">S/{{ $prod_size->price }}</p>
                    @endforeach
                    {{-- si es precio por solo color o simple --}}
                  @else
                    <p class="font-bold">S/{{ $product->price }}</p>
                  @endif
                </td>

                {{-- Precio Oferta --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  @if (count($product->color_product_size))
                    @foreach ($product->color_product_size as $color_prod_size)
                      @if ($color_prod_size->offer_price > 0)
                        <p class="text-orange-500 font-bold">
                          S/{{ $color_prod_size->offer_price }}</p>
                      @else
                        -
                      @endif
                      {{-- La fecha de oferta puede ser indefinida osea vacio --}}
                      {{-- por lo tanto, el precio de oferta es indeterminado --}}
                      @if ($color_prod_size->offer_date !== '')
                        <p class="text-orange-500 font-bold">
                          {{ $color_prod_size->offer_date }}</p>
                      @endif
                    @endforeach
                  @elseif (count($product->product_size))
                    @foreach ($product->product_size as $prod_size)
                      @if ($prod_size->offer_price > 0)
                        <p class="text-orange-500 font-bold">
                          S/{{ $prod_size->offer_price }}</p>
                      @else
                        -
                      @endif
                      {{-- La fecha de oferta puede ser indefinida osea vacio --}}
                      {{-- por lo tanto, el precio de oferta es indeterminado --}}
                      @if ($prod_size->offer_date !== '')
                        <p class="text-orange-500 font-bold">
                          {{ $prod_size->offer_date }}</p>
                      @endif
                    @endforeach
                  @else
                    @if ($product->offer_price > 0)
                      <p class="text-orange-500 font-bold">
                        S/{{ $product->offer_price }}</p>
                    @else
                      -
                    @endif
                    {{-- La fecha de oferta puede ser indefinida osea vacio --}}
                    {{-- por lo tanto, el precio de oferta es indeterminado --}}
                    @if ($product->offer_date !== '')
                      <p class="text-orange-500 font-bold">
                        {{ $product->offer_date }}</p>
                    @endif
                  @endif
                </td>
                {{-- Stock o quantity com bd de productos --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{-- si es cantidad por color y talla --}}
                  @if (count($product->color_product_size))
                    @foreach ($product->color_product_size as $color_prod_size)
                      <p class="font-bold">S/{{ $color_prod_size->price }}</p>
                    @endforeach
                    {{-- si es cantidad por solo talla --}}
                  @elseif (count($product->product_size))
                    @foreach ($product->product_size as $prod_size)
                      <div class="flex items-center mb-2">
                        <div class="mr-2 font-bold">
                          {{ $prod_size->size->name }}:
                        </div>
                        <p class="text-orange-500 font-bold mr-2">
                          {{ $prod_size->quantity }}
                        </p>
                      </div>
                    @endforeach
                    {{-- si es cantidad por solo color --}}
                  @elseif (count($product->color_product))
                    @foreach ($product->color_product as $key => $p_color)
                      <div class="flex items-center mb-2">
                        <div class="w-6 h-6 rounded-full mr-2 tooltip-tippy"
                          data-tippy-content="{{ $p_color->color->name }}"
                          style="background-color: {{ $p_color->color->hex }}"></div>
                        <p class="text-orange-500 font-bold">
                          {{ $p_color->quantity }}
                        </p>
                      </div>
                    @endforeach
                  @else
                    <p class="text-orange-500 font-bold">{{ $product->quantity }}</p>
                  @endif
                  {{-- @if ($product->color_product->count())
                            @foreach ($product->color_product as $key => $p_color)
                            <div class="flex mb-2">
                                <p class="text-orange-500 font-bold mr-2">
                                    {{ $p_color->quantity }}
              </p>
              <div class="w-6 h-6 rounded-full" style="background-color: {{ $p_color->color->hex }}">
              </div>
  </div>
  @endforeach
  @else
  <p class="text-orange-500 font-bold">{{ $product->quantity }}</p>
  @endif --}}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @livewire('admin.change-status-product', ['item_id' => $product->id], key($product->id))
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <a href="{{ route('admin.products.edit', $product) }}"
                      class="text-indigo-600 hover:text-indigo-900">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    {{-- TODO: terminar eliminar producto --}}
                    {{-- <span wire:click="$emit('deleteProduct', '{{ $product->id }}')"
      class="text-red-600 hover:text-red-900 cursor-pointer">
      <i class="fas fa-trash-alt"></i>
      </span> --}}
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="px-6 py-4">
          No hay ningún registro coincidente
        </div>
      @endif


      @if ($products->hasPages())
        <div class="px-6 py-4">
          {{ $products->links() }}
        </div>
      @endif
    </x-table-responsive>
  </div>
  @push('scripts')
    <script>
      tippy('.tooltip-tippy', {
        // default
        placement: 'top',
      });

      Livewire.on('deleteProduct', productId => {
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
            Livewire.emitTo('admin.show-products', 'delete', productId);

            Swal.fire(
              'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
            )
          }
        })
      })
    </script>
  @endpush
</div>
