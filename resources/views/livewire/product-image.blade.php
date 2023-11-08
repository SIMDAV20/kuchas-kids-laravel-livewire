<article class="pt-2 product-image">
  <a href="{{ route('products.show', $slug) }}">
    @switch($product->type_variant)
    @case('base')
    <img class="h-48 w-full object-contain object-center rounded-t product-image" src="{{ @Storage::url(json_decode(@$product->gallery)[0]) }}">
    @break

    @case('colors')
    @php
    $p_color_prod = $product->color_product->first();
    @endphp
    <img class="h-48 w-full object-contain object-center rounded-t product-image" src="{{ @Storage::url(json_decode($p_color_prod->gallery)[0]) }}">
    @break

    @case('sizes')
    @php
    $p_prod_size = $product->product_size->first();
    @endphp
    <img class="h-48 w-full object-contain object-center rounded-t product-image" src="{{ @Storage::url(json_decode($p_prod_size->gallery)[0]) }}">
    @break

    {{-- @case('color_sizes')
      color_sizes
    @break --}}
    @endswitch

    <div class="p-2">
      <h2 class="prod-title text-gray-550 text-center">
        {{ $product->name }}
      </h2>
    </div>

    {{-- <p class="font-bold text-gray-550">Desde</p>
      <span class="font-bold text-violet-350">S/{{ $product->getMinPrice() }}</span> --}}

    {{-- @if ($product->max_price)
      <div class="flex justify-center items-center">
        <del class="text-sm text-gray-500 font-bold mr-2">S/ {{ $product->price }}</del>
    <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
    </div>
    @else
    <p class="font-bold text-violet-350">S/ {{ $product->price }}</p>
    @endif --}}
  </a>

  {{-- <div x-data="{ p_color: 0 }">
    <figure> --}}
  {{-- @foreach ($product->color_product as $key => $p_color_prod)


      @endforeach --}}
  {{-- <a href="{{ route('products.show', ['slugProduct' => $p_color_prod->slug]) }}">
  <img class="h-48 w-full object-contain object-center rounded-t product-image" :class="p_color == {{ $key }} ? '' : 'hidden'" src="{{ @Storage::url(json_decode($p_color_prod->gallery)[0]) }}">
  </a> --}}
  {{-- @empty
        <a href="{{ route('products.show', ['slugProduct' => $product]) }}">
  <img class="h-48 w-full object-contain object-center rounded-t product-image" src="{{ Storage::url(@$product->images->first()->url) }}" alt="{{ @$product->images->first()->url }}">
  </a>
  @endforelse --}}
  {{-- </figure>
    <div class="py-4 px-2 text-center"> --}}
  {{-- NOMBRE DEL PRODUCTO --}}
  {{-- @forelse ($product->color_product as $key => $p_color_prod)
        <h1 class="text-lg font-semibold mb-2 text-gray-550">
          <a :class="p_color == {{ $key }} ? '' : 'hidden'"
  href="{{ route('products.show', ['slugProduct' => $product]) }}">
  {{ Str::limit($product->name, 40) }}
  </a>
  </h1>
  @empty
  <h1 class="text-lg font-semibold mb-2 text-gray-550">
    <a href="{{ route('products.show', ['slugProduct' => $product]) }}">{{ Str::limit($product->name, 25) }}</a>
  </h1>
  @endforelse --}}
  {{-- BOLITAS DE COLORES --}}
  {{-- @if (count($product->color_product))
        <div class="flex mb-4 justify-center items-center">
          @foreach ($product->color_product as $key => $p_color_prod)
            <div style="background-color: {{ $p_color_prod->color->hex }}"
  class="w-8 h-8 mx-2 rounded-full cursor-pointer" x-on:click="p_color = {{ $key }}">
  </div>
  @endforeach
  </div>
  @endif --}}
  {{-- PRECIO --}}
  {{-- @if (count($product->product_size) > 0 || count($product->color_product_size) > 0)
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
  @endif --}}
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
  {{-- </div> --}}
  {{-- </div> --}}
</article>
