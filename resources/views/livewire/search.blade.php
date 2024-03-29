{{-- BUSCADOR DEL NAVBAR CLIENTE  --}}
<div class="flex-1 relative" x-data>
  <form action="{{ route('search') }}" autocomplete="off">
    <x-input name="name" wire:model="search" type="text" style="focus:outline " class="w-full text-gray-350"
      placeholder="Buscar Producto" />
    <button class="absolute top-0 right-0 w-12 h-full bg-gray-350 flex items-center justify-center rounded-r-md">
      {{-- definir el tamaño del search icon --}}
      <x-search size="35" color="white" />
    </button>
  </form>

  <div class="absolute w-full mt-1 hidden z-50" :class="{ 'hidden': !$wire.open }" @click.away="$wire.open = false">
    <div class="bg-white rounded-lg shadow">
      <div class="px-4 py-3 space-y-2">
        @forelse ($products as $product)
          {{-- SI TIENE COLORES --}}
          @if (count($product->color_product))
            @foreach ($product->color_product as $key => $p_color_prod)
              <a href="{{ route('products.show', ['slugProduct' => $product, 'color' => $p_color_prod->color->slug]) }}"
                class="flex">
                <img class="h-16 w-16 object-contain object-center"
                  src="{{ @Storage::url($p_color_prod->images->first()->url) }}"
                  alt="{{ @$p_color_prod->images->first()->url }}">
                <div class="ml-4 text-gray-700">
                  <p class="text-lg font-semibold leading-5">{{ $product->name }}
                    {{ $p_color_prod->color->name }}</p>
                  <p>Categoría: {{ $product->subcategory->category->name }}</p>
                  <p>
                    @if ($product->offer_price)
                      <div class="flex justify-items-start items-center">
                        <del class="text-sm text-gray-500 font-bold mr-2">S/
                          {{ $product->price }}</del>
                        <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
                      </div>
                    @else
                      <p class="font-bold text-violet-350">S/ {{ $product->price }}</p>
                    @endif
                  </p>
                </div>
              </a>
            @endforeach
          @else
            <a href="{{ route('products.show', ['slugProduct' => $product]) }}" class="flex">
              <img class="h-16 w-16 object-contain object-center"
                src="{{ @Storage::url($product->images->first()->url) }}" alt="{{ @$product->images->first()->url }}">
              <div class="ml-4 text-gray-700">
                <p class="text-lg font-semibold leading-5">{{ $product->name }}</p>
                <p>Categoría: {{ $product->subcategory->category->name }}</p>
                <p>

                  @if (count($product->product_size) > 0 || count($product->color_product_size) > 0)
                    <p class="font-bold text-gray-550">Desde</p>
                    <span class="font-bold text-violet-350">S/{{ $product->getMinPrice() }}</span>
                  @else
                    @if ($product->offer_price)
                      <div class="flex items-center">
                        <del class="text-sm text-gray-500 font-bold mr-2">S/
                          {{ $product->price }}</del>
                        <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
                      </div>
                    @else
                      <p class="font-bold text-violet-350">S/ {{ $product->price }}</p>
                    @endif
                  @endif
                </p>
              </div>
            </a>
          @endif
          <hr class="my-3">
        @empty
          <p class="text-lg leading-5">
            Ningún producto coincide con esos parámetros
          </p>
        @endforelse
      </div>
    </div>
  </div>
</div>
