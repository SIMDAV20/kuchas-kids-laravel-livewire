@props(['id' => null, 'maxWidth' => null, 'items' => [], 'order' => []])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4 mt-10">
        <h1 class="text-lg font-semibold text-gray-700">Stock insuficiente, vuelva a ingresar la cantidad</h1>
    </div>
    <table class="table-auto w-full">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Producto
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Precio
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <span class="ml-5">Cantidad</span>
                </th>
                <th scope="col"
                    class="px-6 py-3 text-left ml-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total
                </th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($items as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-16 w-16 object-contain ml-4" src="{{ $item->options->image }}"
                                    alt="">
                                {{-- <article class="flex justify-center flex-col">
                                    <h1 class="font-bold">{{ $item->name }}</h1>
                                    <div class="flex text-xs">
                                        @isset($item->options->color)
                                            Color: {{ __($item->options->color) }}
                                        @endisset
                                        @isset($item->options->size)
                                            Talla: {{ $item->options->size }}
                                        @endisset
                                    </div>
                                </article> --}}
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        S/ {{ $item->price }}
                    </td>
                    <td class="text-center">
                        {{-- <input type="number" wire:model.lazy="newqty" min="1" class="border p-1 w-16">
                        <span>{{ $newqty }}</span> --}}
                        @livewire('update-order-item', ['orderItem' => $item], key($item->id))
                        {{-- {{ $item->qty }} --}}
                    </td>
                    <td class="text-center">
                        S/ {{ $item->price * $item->qty }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="bg-white rounded-lg shadow-lg px-6 py-4 mt-4">
        <div class="flex justify-between items-center"></div>
        <div> </div>
        <a href="{{ route('orders.payment', $order) }}"
            class="inline-flex justify-center items-center px-4 py-2 bg-indigo-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase
                tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-50 transition">
            Enviar
        </a>
    </div>
</x-modal>
