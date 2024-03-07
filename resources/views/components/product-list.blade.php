@props(['product'])

<li class="bg-white rounded-lg shadow my-4">
  <article class="md:flex" x-data="{ p_color: 0 }">
    <figure>
      @forelse ($product->color_product as $key => $p_color_prod)
        <a href="{{ route('products.show', ['slugProduct' => $product, 'color' => $p_color_prod->color->slug]) }}">
          <img class="h-64 w-64 object-cover object-center rounded-t"
            :class="p_color == {{ $key }} ? '' : 'hidden'"
            src="{{ Storage::url($p_color_prod->images->first()->url) }}"
            alt="{{ Storage::url($p_color_prod->images->first()->url) }}">
        </a>
      @empty
        <a href="{{ route('products.show', $product) }}">
          <img class="h-64 w-64 object-cover object-center rounded-t"
            src="{{ Storage::url(@$product->images->first()->url) }}"
            alt="{{ Storage::url(@$product->images->first()->url) }}">
        </a>
      @endforelse
    </figure>

    <div class="flex-1 py-4 px-6 flex flex-col">
      <div class="lg:flex justify-between mb-4">
        <div>
          <h1 class="text-lg font-semibold text-violet-350">{{ $product->name }}</h1>
          <hr class="my-1">
          @if ($product->offer_price)
            <del class="text-sem font-bold text-gray-350">S/ {{ $product->price }}</del>
            <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
          @else
            <p class="font-bold text-violet-350">S/ {{ $product->price }}</p>
          @endif
        </div>

      </div>

      @if (count($product->color_product))
        <div class="flex mb-4">
          @foreach ($product->color_product as $key => $p_color_prod)
            <div style="background-color: {{ $p_color_prod->color->hex }}"
              class="w-8 h-8 mr-3 rounded-full cursor-pointer hover:border-white hover:outline-gray"
              :class="p_color == {{ $key }} ? 'outline-gray' : ''"
              x-on:click="p_color = {{ $key }}"></div>
          @endforeach
        </div>
      @endif

      <div class="mb-4 text-gray-550">
        <p>{!! Str::limit($product->description, 300) !!}</p>
      </div>

      <div class="mt-4 md:mt-auto mb-2">
        @forelse ($product->color_product as $key => $p_color_prod)
          <a href="{{ route('products.show', ['slugProduct' => $product, 'color' => $p_color_prod->color->slug]) }}"
            class="inline-flex items-center justify-center px-4 py-2 bg-violet-350 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-500 focus:outline-none focus:border-violet-700 focus:shadow-outline-violet active:bg-violet-600 disabled:opacity-25 transition"
            :class="p_color == {{ $key }} ? '' : 'hidden'">
            Más información
          </a>
        @empty

          <a href="{{ route('products.show', ['slugProduct' => $product]) }}"
            class="inline-flex items-center justify-center px-4 py-2 bg-violet-350 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-500 focus:outline-none focus:border-violet-700 focus:shadow-outline-violet active:bg-violet-600 disabled:opacity-25 transition">
            Más información
          </a>
        @endforelse
      </div>
    </div>
  </article>
</li>
