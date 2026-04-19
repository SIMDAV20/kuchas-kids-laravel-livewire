{{-- BUSCADOR DEL NAVBAR CLIENTE  --}}
<div class="flex-1 relative" x-data>
  <form action="{{ route('search') }}" autocomplete="off">
    <x-input name="name" wire:model="search" type="text" style="focus:outline " class="w-full text-gray-350"
      placeholder="Buscar Producto" />
    <button class="absolute top-0 right-0 w-12 h-full bg-gray-350 flex items-center justify-center rounded-r-md">
      <x-search size="35" color="white" />
    </button>
  </form>

  <div class="absolute w-full mt-1 hidden z-50" :class="{ 'hidden': !$wire.open }" @click.away="$wire.open = false">
    <div class="bg-white rounded-lg shadow">
      <div class="px-4 py-3 space-y-2">
        @forelse ($products as $product)
          @php $firstImage = $product->assigned_images->first(); @endphp
          <a href="{{ route('products.show', ['slugProduct' => $product]) }}" class="flex">
            <img class="h-16 w-16 object-contain object-center"
              src="{{ $firstImage ? Storage::url($firstImage->url) : asset('img/no-image.png') }}"
              alt="{{ $product->name }}">
            <div class="ml-4 text-gray-700">
              <p class="text-lg font-semibold leading-5">{{ $product->name }}</p>
              <p>Categoría: {{ $product->subcategory->category->name }}</p>
              <p>
                @if ($product->offer_price > 0)
                  <div class="flex justify-items-start items-center">
                    <del class="text-sm text-gray-500 font-bold mr-2">S/ {{ $product->price }}</del>
                    <span class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</span>
                  </div>
                @else
                  <span class="font-bold text-violet-350">S/ {{ $product->getMinPrice() }}</span>
                @endif
              </p>
            </div>
          </a>
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
