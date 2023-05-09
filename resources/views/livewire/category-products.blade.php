{{-- que se ejecute el metofo loadPots comunicatando en el app\http\livewire\CategoryProducts--}}
<div wire:init="loadProducts">
    {{-- si viene vacio se considera falso --}}
    @if (!$isLoading)
        <div class="glider-contain">
            <ul class="glider-{{ $category->id }} pb-5 pt-3">
                @foreach ($products as $product)
                    <li class="bg-white rounded-lg shadow-lg {{ $loop->last ? '' : 'sm:mr-4' }}">
                        @livewire('product-image', ['product' => $product], key($product->id))
                    </li>
                @endforeach
            </ul>

            <button aria-label="Previous" class="glider-prev">«</button>
            <button aria-label="Next" class="glider-next">»</button>
            <div role="tablist" class="dots"></div>
        </div>
    @else
        {{-- SPINNER --}}
        <div class="mb-4 h-48 flex justify-center items-center bg-white shadow-xl border border-gray-100 rounded-lg">
            <div class="rounded animate-spin ease duration-300 w-10 h-10 border-2 border-blue-500"></div>
        </div>
    @endif
</div>
