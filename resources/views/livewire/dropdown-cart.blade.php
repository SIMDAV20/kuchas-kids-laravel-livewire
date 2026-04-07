<div>
    <x-dropdown width="96">
        <x-slot name="trigger">
            <span class="relative inline-block cursor-pointer">
                {{-- icon cart --}}
                <x-cart color="gray" size="30" />

                @if (Cart::count())
                    <span
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                        {{ Cart::count() }}
                    </span>
                @else
                    <span
                        class="absolute top-0 right-0 inline-block w-2 h-2 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"></span>
                @endif
            </span>
        </x-slot>
        <x-slot name="content">
            <ul class="overflow-y-auto" style="max-height: calc(100vh - 200px)">
                @forelse (Cart::content() as $item)
                    {{-- {{ @$item }} --}}
                    <li class="flex p-2 border-b border-gray-200">
                        <img class="h-15 w-20 object-cover mr-4" src="{{ $item->options->image }}" alt="">

                        <article class="flex-1">
                            <h1 class="font-bold">
                                {{ $item->name }}
                            </h1>
                            <div class="text-xs text-gray-500 mt-1">
                                @foreach($item->options as $key => $value)
                                    @if(!in_array($key, ['image', 'base_price', 'variant_id']))
                                        <span class="mr-2"><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</span>
                                    @endif
                                @endforeach
                            </div>

                            <div>
                                @if (isset($item->options['base_price']))
                                    <div class="flex justify-start items-center">
                                        <del class="text-sm text-gray-500 font-bold mr-2">S/
                                            {{ $item->options['base_price'] * $item->qty }}</del>
                                        <p class="text-violet-350 font-bold">S/ {{ $item->price * $item->qty }}</p>
                                    </div>
                                @else
                                    <p class="font-bold">S/ {{ $item->price * $item->qty }}</p>
                                @endif
                            </div>
                        </article>
                    </li>
                @empty
                    <li class="py-6 px-4">
                        <p class="text-center text-gray-700">No tiene agregado ningún item en el carrito</p>
                    </li>
                @endforelse
            </ul>

            @if (Cart::count())
                <div class="py-2 px-3">
                    <p class="text-lg text-right mr-2 text-gray-700 my-2">
                        <span class="font-bold">Subtotal: </span>
                        S/{{ Cart::subtotal() }}
                    </p>

                    <div class="flex">
                        <a href="{{ route('shopping-cart') }}"
                            class="text-white w-full bg-blue-500 border-l border-t border-b border-blue-500 hover:bg-blue-400 active:bg-blue-600 font-bold uppercase text-xs px-4 py-2 rounded-l outline-none focus:outline-none mb-1 ease-linear transition-all duration-150 text-center">
                            Ir al carrito de compras
                        </a>
                        <a href="{{ route('orders.create') }}"
                            class="text-white w-full bg-violet-350 border-t border-b border-r border-violet-350 hover:bg-violet-400 active:bg-violet-600 font-bold uppercase text-xs px-4 py-2 rounded-r outline-none focus:outline-none mb-1 ease-linear transition-all duration-150
                                flex items-center justify-center
                            ">
                            Ir a pagar
                        </a>
                    </div>
                </div>
            @endif
        </x-slot>
    </x-dropdown>
</div>
