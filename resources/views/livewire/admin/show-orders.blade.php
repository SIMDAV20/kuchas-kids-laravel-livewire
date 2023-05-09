<div class="py-12">
    <x-table-responsive>
        <div class="px-6 py-4 inline-flex w-full">

            <x-input type="text" wire:model="search" placeholder="Ingrese la orden que quiere buscar" class="w-full" />

            <x-button-enlace class="ml-3 text-center" href="{{ route('admin.orders.index') }}">
                VER TODOS
            </x-button-enlace>
        </div>

        @if ($orders->count())
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Orden
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente / Para quien
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dirección
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #productos
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Medio Pago
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        {{-- <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            RECIBO
                        </th> --}}
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-center">
                                    {{-- <span class="w-12 text-center"> --}}
                                    @switch($order->status)
                                        @case(1)
                                            <i class="fas fa-business-time text-red-500 opacity-50"></i>
                                        @break

                                        @case(2)
                                            <i class="fas fa-credit-card text-gray-500 opacity-50"></i>
                                        @break

                                        @case(3)
                                            <i class="fas fa-truck text-yellow-500 opacity-50"></i>
                                        @break

                                        @case(4)
                                            <i class="fas fa-check-circle text-pink-500 opacity-50"></i>
                                        @break

                                        @case(5)
                                            <i class="fas fa-times-circle text-green-500 opacity-50"></i>
                                        @break

                                        @default
                                    @endswitch

                                    <span>
                                        {{ $order->id }}
                                        <br>
                                        {{ $order->created_at->format('d/m/y H:m') }}
                                    </span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span>
                                    @if (isset($order->other_contact))
                                        {{ $order->user->name }} <br>
                                        {{ $order->other_contact }}
                                    @else
                                        {{ $order->contact }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    @$envio = json_decode($order->envio);
                                    if (isset($envio)) {
                                        $envio = [$envio];
                                    }
                                @endphp
                                @if (isset($envio))
                                    {{ @$envio[0]->department }} -
                                    {{ @$envio[0]->province }} -
                                    {{ @$envio[0]->district }} <br>
                                    {{ @$envio[0]->address }}
                                @else
                                    @if ($order->envio_type == 1)
                                        Recojo en tienda
                                    @elseif ($order->envio_type == 3)
                                        A coordinar
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->qty_prods }} Unid.
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    @if ($order->payment_method == 1)
                                        <p class="text-sm text-red-500 font-bold">IZIPAY</p>
                                    @elseif ($order->payment_method == 2)
                                        <p class="font-bold text-purple-700">Yape</p>
                                    @else
                                        <p class="font-bold text-dark">sin pago</p>
                                    @endif
                                </div>

                                <span class="text-sm">
                                    S/ {{ $order->total }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold">
                                    @switch($order->status)
                                        @case(1)
                                            <span class="text-red-500">Pendiente</span>
                                        @break

                                        @case(2)
                                            <span class="text-gray-500">Recibido</span>
                                        @break

                                        @case(3)
                                            <span class="text-yellow-500">Enviado</span>
                                        @break

                                        @case(4)
                                            <span class="text-pink-500">Entregado</span>
                                        @break

                                        @case(5)
                                            <span class="text-green-500">Anulado</span>
                                        @break

                                        @default
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a class="tooltip-tippy" data-tippy-content="Ver Orden"
                                    href="{{ route('admin.orders.show', $order) }}">
                                    <i class="fas fa-eye text-blue-600 hover:text-blue"></i>
                                </a>

                                <a class="tooltip-tippy" data-tippy-content="Adjuntar Recibo"
                                    wire:click="editImages('{{ $order->id }}')">
                                    @if (@$order->images->url)
                                        <i
                                            class="fa fa-file-image text-pink-400 hover:text-pink-600 cursor-pointer"></i>
                                    @else
                                        <i
                                            class="fa fa-file-image text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                No existe registro de órdenes
            </div>
        @endif


        @if ($orders->hasPages())
            <div class="px-6 py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </x-table-responsive>

    {{-- Modal --}}
    <x-dialog-modal wire:model="editForm.open">
        <x-slot name="title">
            Adjuntar Recibo de la Orden: {{ $editForm['id'] }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-3">
                <div>
                    {{-- @if ($editImage)
                        <img class="w-full h-full object-cover object-center" src="{{ @$editImage->temporaryUrl() }}"
                            alt=""> --}}
                    @if ($editForm['image'])
                        @php
                            $fname = Storage::url(@$editForm['image']);
                            $ext = (new SplFileInfo($fname))->getExtension();
                        @endphp
                        @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')
                            <img class="w-full h-full object-cover object-center" src="{{ $fname }}"
                                alt="{{ $editForm['image'] }}">
                        @elseif ($ext == 'pdf' || $ext == 'xlsx' || $ext == 'xls')
                            @php
                                $name = explode('_', (new SplFileInfo($fname))->getFilename())[0];
                                $icons = [
                                    'pdf' => ['<i class="mr-2 fas fa-file-pdf"></i>', 'text-red-500 hover:text-red-400'],
                                    'xlsx' => ['<i class="mr-2 fas fa-file-excel"></i>', 'text-green-500 hover:text-green-400'],
                                    'xls' => ['<i class="mr-2 fas fa-file-excel"></i>', 'text-green-500 hover:text-green-400'],
                                ];
                            @endphp
                            <x-label>Archivo</x-label>
                            <a href="{{ $fname }}" class="{{ $icons[$ext][1] }}"
                                target="_blank">{!! $icons[$ext][0] !!}{{ $name }}</a>
                        @endif
                    @endif
                </div>
                <div>
                    <x-label>
                        Imagen
                    </x-label>
                    <x-input wire:model.defer="editImage" type="file" class="w-full mt-1" />
                    <div wire:loading wire:target="editImage">Cargando...</div>

                    <x-input-error for="editImage" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('editForm.open', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="updateImages()" wire:loading.attr="disabled" wire:target="editImage, updateImages">
                Enviar
            </x-button>
        </x-slot>
    </x-dialog-modal>

    @push('scripts')
        <script>
            tippy('.tooltip-tippy', {
                // default
                placement: 'top',
            });
        </script>
    @endpush
</div>
