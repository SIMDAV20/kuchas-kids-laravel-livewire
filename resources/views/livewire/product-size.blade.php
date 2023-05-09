<div>
    <div class="mb-2 mt-4" x-data="{ p_talla: @entangle('p_talla') }">
        <div class="mb-2">
            {{-- {{ $price }}
            {{ $offer_price }} --}}
            @if ($offer_price > 0)
                <del class="text-lg font-semibold text-gray-550 mr-3">S/ {{ $price }}</del>
                <p class="text-2xl font-semibold text-violet-350">S/ {{ $offer_price }}</p>
            @else
                <p class="text-2xl font-semibold text-gray-550">S/ {{ $price }}</p>
            @endif
        </div>

        <div class="flex">
            @foreach ($product->product_size as $key => $prod_size)
                <button type="button" class="p-2 mr-2 bg-white"
                    :class="p_talla == {{ $key }} ? 'border-2 border-violet-350' : '' "
                    {{ $prod_size->quantity <= 0 ? 'disabled' : '' }}
                    wire:click="show_price({{ $key }})"
                >
                    {{  $prod_size->size->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>
