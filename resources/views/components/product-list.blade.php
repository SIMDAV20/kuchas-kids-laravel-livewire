@props(['product'])

@php $firstImage = $product->assigned_images->first(); @endphp

<li class="bg-white rounded-lg shadow my-4">
  <article class="md:flex">
    <figure>
      <a href="{{ route('products.show', $product) }}">
        <img class="h-64 w-64 object-cover object-center rounded-t"
          src="{{ $firstImage ? Storage::url($firstImage->url) : asset('img/no-image.png') }}"
          alt="{{ $product->name }}">
      </a>
    </figure>

    <div class="flex-1 py-4 px-6 flex flex-col">
      <div class="lg:flex justify-between mb-4">
        <div>
          <h1 class="text-lg font-semibold text-violet-350">{{ $product->name }}</h1>
          <hr class="my-1">
          @if ($product->variants->count() > 0)
            <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Desde</p>
            <p class="font-bold text-violet-350">S/ {{ number_format($product->getMinPrice(), 2) }}</p>
          @elseif ($product->offer_price > 0)
            <del class="text-sm font-bold text-gray-350">S/ {{ $product->price }}</del>
            <p class="text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
          @else
            <p class="font-bold text-violet-350">S/ {{ $product->getMinPrice() }}</p>
          @endif
        </div>
      </div>

      <div class="mb-4 text-gray-550">
        <p>{!! Str::limit($product->description, 300) !!}</p>
      </div>

      <div class="mt-4 md:mt-auto mb-2">
        <a href="{{ route('products.show', $product) }}"
          class="inline-flex items-center justify-center px-4 py-2 bg-violet-350 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-500 transition">
          Más información
        </a>
      </div>
    </div>
  </article>
</li>
