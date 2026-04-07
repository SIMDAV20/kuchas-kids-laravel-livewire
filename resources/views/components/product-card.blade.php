@props(['product'])

@php
    use App\Models\Attribute;
    
    // Aseguramos que el producto tenga stock verificado
    $product->onStockToSell();

    $colorAttribute = Attribute::where('name', 'Color')->first();
    $colors = collect();
    
    if ($colorAttribute) {
        $colors = $product->variants()
            ->whereHas('attributeOptions', function($q) use ($colorAttribute) {
                $q->where('attribute_id', $colorAttribute->id);
            })
            ->with(['attributeOptions' => function($q) use ($colorAttribute) {
                $q->where('attribute_id', $colorAttribute->id);
            }])
            ->get()
            ->pluck('attributeOptions')
            ->flatten()
            ->unique('id');
    }
@endphp

<article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
  <figure class="relative aspect-square overflow-hidden bg-gray-100">
    <a href="{{ route('products.show', ['slugProduct' => $product->slug]) }}">
      <img class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-500"
           src="{{ Storage::url($product->images->first()->url ?? 'products/default.png') }}" 
           alt="{{ $product->name }}">
    </a>
    
    {{-- Etiqueta de Oferta --}}
    @if($product->offer_price)
      <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow">
        OFERTA
      </div>
    @endif
  </figure>

  <div class="p-4 text-center">
    {{-- NOMBRE DEL PRODUCTO --}}
    <h1 class="text-sm md:text-base font-semibold text-gray-700 mb-2 h-10 overflow-hidden line-clamp-2">
      <a href="{{ route('products.show', ['slugProduct' => $product->slug]) }}" class="hover:text-violet-600 transition-colors">
        {{ Str::limit($product->name, 50) }}
      </a>
    </h1>

    {{-- BOLITAS DE COLORES (PREVIEW) --}}
    @if ($colors->count() > 0)
      <div class="flex mb-3 justify-center items-center gap-1.5">
        @foreach ($colors as $color)
          <div style="background-color: {{ $color->hex }}"
            class="w-4 h-4 rounded-full border border-gray-200 ring-offset-1 hover:ring-1 ring-gray-400 transition-all" 
            title="{{ $color->value }}">
          </div>
        @endforeach
      </div>
    @endif

    {{-- PRECIO --}}
    <div class="mt-auto">
      @if ($product->variants->count() > 0)
        @php
            $minPrice = $product->variants->min('price');
            $minOffer = $product->variants->where('offer_price', '>', 0)->min('offer_price');
            $finalMin = $minOffer && $minOffer < $minPrice ? $minOffer : $minPrice;
        @endphp
        
        <p class="text-xs text-gray-400 font-medium">Desde</p>
        <span class="text-lg font-bold text-violet-600">S/ {{ number_format($finalMin, 2) }}</span>
      @else
        @if ($product->offer_price)
          <div class="flex justify-center items-center gap-2">
            <del class="text-xs text-gray-400 font-bold">S/ {{ number_format($product->price, 2) }}</del>
            <p class="text-lg text-red-500 font-bold">S/ {{ number_format($product->offer_price, 2) }}</p>
          </div>
        @else
          <p class="text-lg font-bold text-gray-800">S/ {{ number_format($product->price, 2) }}</p>
        @endif
      @endif
    </div>
  </div>
</article>
