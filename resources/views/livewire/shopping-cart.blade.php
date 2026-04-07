<div class="container py-8" style="min-height: 600px">
  <x-table-responsive>
    <div class="px-6 py-4 bg-white">
      <h1 class="text-lg font-semibold text-gray-700">CARRITO DE COMPRAS</h1>
    </div>

    @if (Cart::count())
      <table class="table-auto w-full">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Producto
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Precio
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <span class="ml-5">Cantidad</span>
            </th>
            <th scope="col"
              class="px-6 py-3 text-left ml-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
              Total
            </th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          @foreach (Cart::content() as $item)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-15 w-20 object-cover object-center mr-4" src="{{ $item->options->image }}"
                      alt="{{ $item->options->image }}">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      <span>{{ $item->name }}</span>
                    </div>
                    
                    {{-- Mostrar Atributos Dinámicos --}}
                    <div class="text-xs text-gray-500">
                      @foreach($item->options as $key => $value)
                        @if(!in_array($key, ['image', 'base_price', 'variant_id']))
                          <span class="mr-2"><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</span>
                        @endif
                      @endforeach
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">
                  <span>S/ {{ number_format($item->price, 2) }}</span>
                  <a class="ml-6 cursor-pointer hover:text-red-600" wire:click="delete('{{ $item->rowId }}')"
                    wire:target="delete('{{ $item->rowId }}')" wire:loading.class="text-red-600 opacity-25">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">
                  @livewire('update-cart-item', ['rowId' => $item->rowId], key($item->rowId))
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                S/ {{ $item->price * $item->qty }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="px-6 py-4 bg-white">
        <a wire:click="destroy" class="text-sm cursor-pointer hover:underline mt-4 inline-block">
          <i class="fas fa-trash"></i>
          Borrar carrito de compras
        </a>
      </div>
    @else
      <div class="flex flex-col items-center py-4 bg-white">
        <x-cart />
        <p class="text-lg text-gray-700 mt-4">TU CARRO DE COMPRAS ESTÁ VACÍO</p>
        <x-danger-enlace href="{{ route('welcome') }}" class="mt-4 px-16">
          Ir al inicio
        </x-danger-enlace>
      </div>
    @endif
  </x-table-responsive>


  @if (Cart::count())
    <div class="bg-white rounded-lg shadow-lg px-6 py-4 mt-4">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-gray-700">
            <span class="font-bold text-lg">Total:</span>
            S/ {{ Cart::subTotal() }}
          </p>
        </div>
        <div>
          <x-enlace href="{{ route('orders.create') }}">
            Continuar
          </x-enlace>
        </div>
      </div>

      {{-- <div class=" mt-4">
                @if (Cart::subTotal() > $settings_company->min_amount)
                    <div class="text-greenLime-500 font-bold">
                        El envío va por nuestra cuenta para Lima y Callao.
                    </div>
                @else
                    <div class="text-gray-600">
                        Recuerda que tus compras mayores a S/{{ $settings_company->min_amount }} son <span class="font-bold">gratis</span> para Lima y Callao.
                    </div>
                @endif
            </div> --}}
    </div>
  @endif
</div>
