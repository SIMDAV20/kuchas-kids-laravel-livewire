<article x-data="{ p_color: 0 }">
  <figure>
    {{-- TODO: se debe refactorizar --}}
    @switch($typeProduct)
      @case(1)
        @foreach ($product->color_product as $key => $p_color_prod)
          <a href="{{ route('products.show', ['slugProduct' => $p_color_prod->slug]) }}">
            <img class="w-full object-contain object-center rounded-t product-image"
              :class="p_color == {{ $key }} ? '' : 'hidden'"
              src="{{ @Storage::url($p_color_prod->images->first()->url) }}" alt="{{ @$p_color_prod->images->first()->url }}">
          </a>
        @endforeach
      @break

      @case(2)
        <a href="{{ route('products.show', ['slugProduct' => $product]) }}">
          <img class="w-full object-contain object-center rounded-t product-image"
            src="{{ Storage::url(@$product->product_size->first()->images->first()->url) }}" alt="{{ $product->name }}">
        </a>
      @break

      @default
        <a href="{{ route('products.show', ['slugProduct' => $product]) }}">
          <img class="w-full object-contain object-center rounded-t product-image"
            src="{{ Storage::url(@$product->images->first()->url) }}" alt="{{ $product->name }}">
        </a>
    @endswitch
  </figure>

  <div class="py-4 px-2 text-center">

    {{-- NOMBRE DEL PRODUCTO --}}
    @forelse ($product->color_product as $key => $p_color_prod)
      <h1 class="text-lg font-semibold mb-2 text-gray-550">
        <a :class="p_color == {{ $key }} ? '' : 'hidden'"
          href="{{ route('products.show', ['slugProduct' => $product]) }}">
          {{ Str::limit($product->name, 40) }}
        </a>
      </h1>
    @empty
      <h1 class="text-lg font-semibold mb-2 text-gray-550 prod-title">
        <a href="{{ route('products.show', ['slugProduct' => $product]) }}">{{$product->name}}</a>
      </h1>
    @endforelse

    {{-- BOLITAS DE COLORES --}}
    @if (count($product->color_product))
      <div class="flex mb-4 justify-center items-center">
        @foreach ($product->color_product as $key => $p_color_prod)
          <div style="background-color: {{ $p_color_prod->color->hex }}"
            class="w-8 h-8 mx-2 rounded-full cursor-pointer" x-on:click="p_color = {{ $key }}">
          </div>
        @endforeach
      </div>
    @endif

    {{-- PRECIO --}}
    @if (count($product->product_size) > 0 || count($product->color_product_size) > 0)
      <p class="font-bold text-gray-550">Desde</p>
      <span class="font-bold text-violet-350">S/{{ $product->getMinPrice() }}</span>
    @else
      @if ($product->offer_price)
        <div class="flex justify-center items-center">
          <del class="text-sm text-gray-500 font-bold mr-2">S/ {{ $product->price }}</del>
          <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
        </div>
      @else
        <p class="font-bold text-violet-350">S/ {{ $product->price }}</p>
      @endif
    @endif
    {{-- @if ($low_price < 9999999) @else @if ($product->offer_price > 0 && \Carbon\Carbon::parse($product->offer_date)->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d') && $product->offer_date !== null)
            <div class="flex justify-center items-center">
                <del class="text-sm font-semibold text-gray-400 mr-3">S/ {{ $product->price }}</del>
                <p class="text-violet-350 font-semibold">S/ {{ $product->offer_price }}</p>
            </div>

            @elseif ($product->offer_price > 0 && $product->offer_date == null)
            <div class="flex justify-center items-center">
                <del class="text-sm font-semibold text-gray-400 mr-3">S/ {{ $product->price }}</del>
                <p class="text-violet-350 font-semibold">S/ {{ $product->offer_price }}</p>
            </div>
            @else
            <p class="font-semibold text-gray-550">S/ {{ $product->price }}</p>
            @endif
            @endif --}}

    {{-- @if ($product->offer_price)
            <div class="flex justify-center items-center">
                <del class="text-sm text-gray-500 font-bold mr-2">S/ {{ $product->price }}</del>
                <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
            </div>
            @else
            <p class="font-bold">S/ {{ $product->price }}</p>
            @endif --}}
  </div>
</article>
