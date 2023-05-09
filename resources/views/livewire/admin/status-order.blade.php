<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="flex bg-white rounded-lg px-6 sm:px-12 py-8 mb-6 pt-3 items-center">

        <div class="relative">
            <div
                class="{{ $order->status >= 2 && $order->status != 5 ? 'bg-blue-400' : 'bg-gray-400' }} rounded-full h-12 w-12 flex items-center justify-center">
                <i class="fas fa-check text-white"></i>
            </div>

            <div class="absolute -left-1.5 mt-0.5">
                <p>Recibido</p>
            </div>
        </div>

        <div class="{{ $order->status >= 3 && $order->status != 5 ? 'bg-blue-400' : 'bg-gray-400' }} h-1 flex-1 mx-2">
        </div>

        <div class="relative">
            <div
                class="{{ $order->status >= 3 && $order->status != 5 ? 'bg-blue-400' : 'bg-gray-400' }} rounded-full h-12 w-12 flex items-center justify-center">
                <i class="fas fa-truck text-white"></i>
            </div>

            <div class="absolute -left-1 mt-0.5">
                <p>Enviado</p>
            </div>
        </div>

        <div class="{{ $order->status >= 4 && $order->status != 5 ? 'bg-blue-400' : 'bg-gray-400' }} h-1 flex-1 mx-2">
        </div>

        <div class="relative">
            <div
                class="{{ $order->status >= 4 && $order->status != 5 ? 'bg-blue-400' : 'bg-gray-400' }} rounded-full h-12 w-12 flex items-center justify-center">
                <i class="fas fa-check text-white"></i>
            </div>

            <div class="absolute -left-2 mt-0.5">
                <p>Entregado</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-lg shadow-lg px-6 py-4 mb-6">
        <p class="text-gray-700 uppercase">
            <span class="font-semibold">Orden:</span>
            #{{ $order->id }}
        </p>
        <form wire:submit.prevent="update">
            <div class="flex space-x-3 mt-2">
                <x-label>
                    <input wire:model.defer="status" type="radio" name="status" value="2" class="mr-2">
                    RECIBIDO
                </x-label>
                <x-label>
                    <input wire:model.defer="status" type="radio" name="status" value="3" class="mr-2">
                    ENVIADO
                </x-label>
                <x-label>
                    <input wire:model.defer="status" type="radio" name="status" value="4" class="mr-2">
                    ENTREGADO
                </x-label>
                <x-label>
                    <input wire:model.defer="status" type="radio" name="status" value="5" class="mr-2">
                    ANULADO
                </x-label>
            </div>

            <div class="flex mt-2">
                <x-button class="ml-auto">
                    Actualizar
                </x-button>
            </div>
        </form>
    </div>

    <section class="grid md:grid-cols-2 gap-6 text-sm">
        <aside class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div>
                <p class="text-lg font-semibold uppercase">
                    Envío
                </p>

                @if ($order->other_contact)
                    <p>{{ $order->other_contact }}</p>
                    <p>{{ $order->other_doc_number }}</p>
                    <p>{{ $order->other_phone }}</p>
                @else
                    <p>{{ $order->contact }}</p>
                    <p>{{ $order->doc_number }}</p>
                    <p>{{ $order->phone }}</p>
                @endif

                <hr class="w-full border-t-2 border-b-violet-350 my-2">

                @if ($order->envio_type == 1)
                    <p>Los productos deben ser recogidos en tienda</p>
                    <p>Jr. Brigadier Mateo Pumacahua 2541 Lince, Altura cuadra 11 av. César
                        Vallejo</p>
                @elseif ($order->envio_type == 2)
                    <p>{{ $envio->address }}</p>
                    <p class="mb-4">
                        {{ $envio->department }} - {{ $envio->province }} - {{ $envio->district }}
                    </p>
                    <p>Referencia: {{ $envio->references }}</p>
                @else
                    En coordinar la dirección a través del
                    <br>
                    whatssap <a href="https://wa.me/51960546859" class="text-blue-600 hover:text-blue-900"
                        target="_blank">960546859</a>
                @endif
            </div>
            @if (strlen($order->extra_note) > 0)
                <div class="mt-3">
                    <p class="text-lg font-semibold uppercase">Nota extra</p>

                    {{ $order->extra_note }}
                </div>
            @endif

            @if ($order->status == 5)
                <div class="mt-3">
                    <p class="text-lg font-semibold uppercase">Observación</p>

                    {{ $order->observation }}
                </div>
            @endif
        </aside>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 md gap-6">
                <aside>
                    <p class="text-lg font-semibold uppercase mb-3">
                        Método de pago
                    </p>
                    <div class="flex justify-center flex-col items-center w-full">
                        @if ($order->payment_method == 1)
                            <p class="text-sm text-red-500 font-bold">IZIPAY</p>
                        @elseif ($order->payment_method == 2)
                            <div class="flex items-center">
                                <p class="text-sm text-purple-600 font-bold">YAPE</p>

                                <div class="images ml-4 mt-2">
                                    <img src="{{ @Storage::url($order->payment->images->url) }}" alt=""
                                        width="100" height="50" class="object-contain" />
                                </div>
                            </div>
                        @else
                            <p class="font-bold text-dark">Sin pago</p>
                        @endif

                        <div class="mt-5">
                            @switch(@$order->payment->status)
                                @case(1)
                                    <span class="font-bold text-blue-400">PAGO APROBADO</span>
                                @break

                                @case(2)
                                    <span class="font-bold text-yellow-400">PAGO PENDIENTE</span>
                                @break

                                @case(3)
                                    <span class="font-bold text-orange-400">PAGO RECHAZADO</span>
                                @break

                                @case(4)
                                    <span class="font-bold text-red-600">PAGO ANULADO</span>
                                @break

                                @default
                            @endswitch
                        </div>
                    </div>
                </aside>
                @if ($order->invoice)
                    <aside class="w-full">
                        <p class="text-lg font-semibold uppercase mb-3">
                            DETALLE FACTURACIÓN
                        </p>

                        <div class="text-sm">
                            <p>{{ $order->invoice->ruc }}</p>
                            <p>{{ $order->invoice->company_name }}</p>
                            <p>{{ $order->invoice->company_address }}</p>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white rounded-lg shadow-lg p-6 mb-6 text-gray-700">
        <p class="text-xl font-semibold mb-4">Productos Ordenados</p>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th></th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($items as $item)
                    <tr>
                        <td>
                            <div class="flex flex-col md:flex-row mb-2">
                                <img class="h-15 w-20 object-cover mr-4" src="{{ $item->options->image }}"
                                    alt="">
                                <article>
                                    <h1 class="font-bold">{{ $item->name }}</h1>
                                    <div class="flex text-xs">
                                        @isset($item->options->color)
                                            {{ __($item->options->color) }}
                                        @endisset
                                        @isset($item->options->size)
                                            {{ $item->options->size }}
                                        @endisset
                                    </div>
                                </article>
                            </div>
                        </td>
                        <td class="text-center">
                            S/ {{ $item->price }}
                        </td>
                        <td class="text-center">
                            {{ $item->qty }}
                        </td>
                        <td class="text-center">
                            S/ {{ $item->price * $item->qty }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div class="w-full flex items-center justify-end mt-4 pt-3 border-t-2 border-violet-350">
            <p class="text-gray-700 mr-4">
                <span class="font-bold text-lg">Costo de envío:</span>
            </p>
            <p class="text-xl font-bold text-center mr-0 md:mr-4 ">S/ {{ $order->shipping_cost }}</p>
        </div>
        <div class="w-full flex items-center justify-end mt-4">
            <p class="text-gray-700 mr-4">
                <span class="font-bold text-lg">Total:</span>
            </p>
            <p class="text-xl font-bold text-center mr-0 md:mr-4 ">S/ {{ $order->total }}</p>
        </div>
    </section>
</div>

<div id="image-viewer">
    <span class="close">&times;</span>
    <img class="modal-content" id="full-image">
</div>

@push('scripts')
    <script>
        $(".images img").click(function() {
            $("#full-image").attr("src", $(this).attr("src"));
            $('#image-viewer').show();
        });

        $("#image-viewer .close").click(function() {
            $('#image-viewer').hide();
        });
    </script>
@endpush
