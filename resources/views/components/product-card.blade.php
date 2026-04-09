@props(['product'])

<article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col h-full">
  <figure class="relative aspect-square overflow-hidden bg-gray-100 flex-shrink-0">
    <a href="{{ route('products.show', ['slugProduct' => $product->slug]) }}">
      <img class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-500"
           src="{{ Storage::url($product->getAssignedImagesAttribute()->first()->url ?? 'products/default.png') }}" 
           alt="{{ $product->name }}">
    </a>
    
    {{-- Etiqueta de Oferta --}}
    @if($product->offer_price || ($product->card_has_variants && $product->variants->where('offer_price', '>', 0)->count() > 0))
      <div class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10">
        OFERTA
      </div>
    @endif
  </figure>

  <div class="p-4 flex flex-col flex-1 text-center">
    {{-- NOMBRE DEL PRODUCTO - Ajustado para evitar cortes --}}
    <div class="min-h-[4rem] mb-2">
        <h1 class="text-sm md:text-base font-bold text-gray-700 leading-tight">
            <a href="{{ route('products.show', ['slugProduct' => $product->slug]) }}" class="hover:text-violet-600 transition-colors line-clamp-2 overflow-hidden">
                {{ $product->name }}
            </a>
        </h1>
    </div>

    {{-- BOLITAS DE COLORES (PREVIEW) --}}
    @if (isset($product->card_colors) && $product->card_colors->count() > 0)
      <div class="flex mb-4 justify-center items-center gap-1.5 min-h-[1.5rem]">
        @foreach ($product->card_colors as $color)
          <div style="background-color: {{ $color->hex }}"
            class="w-3.5 h-3.5 rounded-full border border-gray-200 ring-offset-1 hover:ring-1 ring-gray-400 transition-all cursor-default" 
            title="{{ $color->value }}">
          </div>
        @endforeach
      </div>
    {{-- @else
      <div class="mb-4 min-h-[1.5rem]"></div> --}}
    @endif

    {{-- PRECIO - Siempre al final --}}
    <div class="mt-auto">
      @if ($product->card_has_variants)
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Desde</p>
        <span class="text-xl font-black text-violet-350">S/ {{ number_format($product->card_min_price, 2) }}</span>
      @else
        @if ($product->offer_price)
          <div class="flex flex-col items-center">
            <del class="text-xs text-gray-400 font-bold mb-0.5">S/ {{ number_format($product->price, 2) }}</del>
            <p class="text-xl text-red-500 font-black">S/ {{ number_format($product->offer_price, 2) }}</p>
          </div>
        @else
          <p class="text-xl font-black text-gray-800">S/ {{ number_format($product->price, 2) }}</p>
        @endif
      @endif
    </div>
  </div>
</article>
