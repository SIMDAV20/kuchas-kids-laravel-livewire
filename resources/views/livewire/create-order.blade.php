<div class="container py-8 grid lg:grid-cols-2 xl:grid-cols-5 gap-6" style="min-height: 600px">

    @if (count(Cart::content()) > 0)

        <div class="order-2 lg:order-1 lg:col-span-1 xl:col-span-3">
            <p class="my-3 text-lg text-gray-700 font-semibold">Datos Personales</p>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-4">
                    <div class="flex items-center">
                        <x-label value="Nombre de contacto" obligatory="true" />
                        <span class="text-red-600 ml-1">*</span>
                    </div>
                    {{-- wire:model.defer una vez continue con el form --}}
                    <x-input type="text" wire:model.defer="contact"
                        placeholder="Ingrese el nombre de la persona que recibirá el producto" class="w-full" />
                    <x-input-error for="contact" />
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <x-label value="Teléfono de contacto" />
                        <span class="text-red-600 ml-1">*</span>
                    </div>
                    <x-input type="text" wire:model.defer="phone"
                        placeholder="Ingrese el número de teléfono de contacto" class="w-full" />
                    <x-input-error for="phone" />
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <x-label value="Documento de indentidad" />
                        <span class="text-red-600 ml-1">*</span>
                    </div>
                    <x-input type="text" wire:model.defer="doc" placeholder="Ingrese el número de DNI"
                        class="w-full" />
                    <x-input-error for="doc" />
                </div>
            </div>

            <div x-data="{ other_person: @entangle('other_person') }" class="mt-4">
                <label class="bg-white rounded-lg shadow px-6 py-4 flex items-center justify-between mb-4">
                    <span class="ml-2 text-gray-700 mr-2">
                        Lo recibirá otra persona ?
                    </span>
                    <div class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
                        <input x-model="other_person" type="checkbox" name="toggle-one" id="toggle-one"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                        <label for="toggle-one"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer">
                        </label>
                    </div>
                </label>

                <div x-show="other_person" class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4">
                        <div class="flex items-center">
                            <x-label value="Nombre de contacto" obligatory="true" />
                            <span class="text-red-600 ml-1">*</span>
                        </div>
                        {{-- wire:model.defer una vez continue con el form --}}
                        <x-input type="text" wire:model.defer="other_contact"
                            placeholder="Ingrese el nombre de la persona que recibirá el producto" class="w-full" />
                        <x-input-error for="other_contact" />
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <x-label value="Teléfono de contacto" />
                            <span class="text-red-600 ml-1">*</span>
                        </div>
                        <x-input type="text" wire:model.defer="other_phone"
                            placeholder="Ingrese el número de teléfono de contacto" class="w-full" />
                        <x-input-error for="other_phone" />
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <x-label value="Documento de indentidad" />
                            <span class="text-red-600 ml-1">*</span>
                        </div>
                        <x-input type="text" wire:model.defer="other_doc" placeholder="Ingrese el número de DNI"
                            class="w-full" />
                        <x-input-error for="other_doc" />
                    </div>
                </div>
            </div>

            <div x-data="{ envio_type: @entangle('envio_type') }">
                <p class="mt-6 mb-3 text-lg text-gray-700 font-semibold">
                    Envío
                </p>
                {{-- flex para que ocupe todo el espacio disponible --}}
                <label class="bg-white rounded-lg shadow px-6 py-4 flex items-center mb-4">
                    <input x-model="envio_type" type="radio" value="1" name="envio_type" class="text-gray-600">

                    <span class="ml-2 text-gray-700">
                        Recoger en módulo de venta
                    </span>

                    <span class="font-semibold text-gray-700 ml-auto">
                        Gratis
                    </span>
                </label>

                <div class="bg-white rounded-lg shadow mb-4">
                    <label class="px-6 py-4 flex items-center cursor-pointer">
                        <input x-model="envio_type" type="radio" value="2" name="envio" class="text-gray-600">
                        <span class="ml-2 text-gray-700">
                            Envío a domicilio
                        </span>
                    </label>
                    {{-- :class="{ 'hidden': envio_type != 2 }" --}}
                    <div x-show="envio_type == 2" class="px-6 pb-6 grid grid-cols-2 gap-6">
                        {{-- Departamentos --}}
                        <div class="col-span-2 sm:col-span-1">
                            <div class="flex items-center">
                                <x-label value="Departamento" />
                                <span class="text-red-600 ml-1">*</span>
                            </div>
                            <select class="form-control w-full" wire:model="department_id">
                                <option value="" disabled selected>Seleccione un departamento</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>

                            <x-input-error for="department_id" />
                        </div>

                        {{-- Provincia --}}
                        <div class="col-span-2 sm:col-span-1">
                            <div class="flex items-center">
                                <x-label value="Provincia" />
                                <span class="text-red-600 ml-1">*</span>
                            </div>

                            <select class="form-control w-full" wire:model="province_id">
                                <option value="" disabled selected>Seleccione una provincia</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>

                            <x-input-error for="province_id" />
                        </div>

                        {{-- Distritos --}}
                        <div class="col-span-2 sm:col-span-1">
                            <div class="flex items-center">
                                <x-label value="Distrito" />
                                <span class="text-red-600 ml-1">*</span>
                            </div>

                            <select class="form-control w-full" wire:model="district_id">
                                <option value="" disabled selected>Seleccione un distrito</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>

                            <x-input-error for="district_id" />
                        </div>

                        @if ($district_id !== '' && $shipping_cost <= 0)
                            <p class="text-red-600 font-bold mt-5">El costo del envio será coordinado de manera externa
                            </p>
                        @endif

                        <div class="col-span-2">
                            <div class="flex items-center">
                                <x-label value="Dirección" />
                                <span class="text-red-600 ml-1">*</span>
                            </div>
                            <x-input class="w-full" wire:model="address" type="text"
                                placeholder="Ingrese la dirección donde quiere que llegue su pedido" />
                            <x-input-error for="address" />
                        </div>

                        <div class="col-span-2">
                            <div class="flex items-center">
                                <x-label value="Referencia" />
                                <span class="text-red-600 ml-1">*</span>
                            </div>
                            <textarea class="form-control w-full" style="resize:none" rows="6" wire:model="references"
                                placeholder="Ingrese una descripción adicional a su dirección"></textarea>
                            <x-input-error for="references" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow mb-4">
                    <label class="px-6 py-4 flex items-center cursor-pointer mb-3">
                        <input x-model="envio_type" type="radio" value="3" name="envio_type"
                            class="text-gray-600">
                        <span class="ml-2 text-gray-700">
                            Estoy en otro destino
                        </span>
                        <span class="font-semibold text-gray-700 ml-auto">
                            coordinación externa
                        </span>
                    </label>
                    <p x-show="envio_type == 3" class="px-6 pb-6">
                        <span>Envíanos un mensaje a nuestro WhatsApp de atención al cliente
                            para agilizar la programación de su envio.</span>
                        <a href="https://wa.me/51960546859" class="text-blue-600 hover:text-blue-900"
                            target="_blank">960546859</a>
                    </p>
                </div>
            </div>

            <div x-data="{ facturacion: @entangle('facturacion') }">
                <p class="mt-6 mb-3 text-lg text-gray-700 font-semibold mr-2">
                    Facturación
                </p>

                <label class="bg-white rounded-lg shadow px-6 py-4 flex items-center justify-between mb-4">
                    <span class="ml-2 text-gray-700 mr-2">
                        Desea Factura ?
                    </span>
                    <div class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
                        <input x-model="facturacion" type="checkbox" name="toggle" id="toggle" maxlength="11"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                        <label for="toggle"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer">
                        </label>
                    </div>
                </label>

                <div x-show="facturacion" class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4">
                        <div class="flex items-center">
                            <x-label value="RUC" />
                            <span class="text-red-600 ml-1">*</span>
                        </div>
                        <x-input type="text" wire:model.defer="ruc" placeholder="Ingrese el número de RUC"
                            class="w-full" />
                        <x-input-error for="ruc" />
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <x-label value="Razón social" />
                            <span class="text-red-600 ml-1">*</span>
                        </div>
                        <x-input type="text" wire:model.defer="social_reason"
                            placeholder="Ingrese la razón social" class="w-full" />
                        <x-input-error for="social_reason" />
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <x-label value="Dirección" />
                            <span class="text-red-600 ml-1">*</span>
                        </div>
                        <x-input type="text" wire:model.defer="address_invoice"
                            placeholder="Ingrese la dirección de la razón social" class="w-full" />
                        <x-input-error for="address_invoice" />
                    </div>
                </div>
            </div>

            <div class="lg:hidden sm:block w-full text-right">
                <x-button wire.loading.attr="disabled" wire.target="create_order" class="mt-6 mb-4"
                    wire:click="create_order">
                    Continuar con la compra
                </x-button>
            </div>
        </div>

        <div class="order-1 lg:order-2 lg:col-span-1 xl:col-span-2">
            <p class="my-3 text-lg text-gray-700 font-semibold">Detalle del Pedido</p>
            <div class="bg-white rounded-lg shadow p-6">
                <ul>
                    @forelse (Cart::content() as $item)
                        <li class="flex p-2 border-b border-gray-200">
                            <img class="h-15 w-20 object-cover mr-4" src="{{ $item->options->image }}"
                                alt="">
                            <article class="flex-1">
                                <h1 cl zone_idass="font-bold">
                                    {{ $item->name }}
                                </h1>
                                <div class="flex">
                                    <p>Cant: {{ $item->qty }}</p>

                                    @isset($item->options['color'])
                                        <p class="mx-2"> - Color: {{ __($item->options['color']) }}</p>
                                    @endisset

                                    @isset($item->options['size'])
                                        <p class="mx-2">{{ $item->options['size'] }}</p>
                                    @endisset
                                </div>
                                <div>
                                    @if (isset($item->options['base_price']))
                                        <div class="flex justify-start items-center">
                                            <del class="text-sm text-gray-500 font-bold mr-2">S/
                                                {{ $item->options['base_price'] * $item->qty }}</del>
                                            <p class="text-violet-350 font-bold">S/ {{ $item->price * $item->qty }}
                                            </p>
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

                <hr class="mt-4 mb-3">

                <div class="text-gray-700">
                    <p class="flex justify-between items-center">
                        Subtotal
                        <span class="font-semibold">S/ {{ Cart::subtotal() }}</span>
                    </p>
                    <p class="flex justify-between items-center">
                        Envío
                        {{-- {{ $settings_company->min_amount }} --}}
                        <span class="font-semibold">
                            @if ($envio_type == 1 && $shipping_cost == 0)
                                Gratis
                            @elseif ($envio_type == 3 && $shipping_cost == 0)
                                Por coordinar
                            @else
                                @if (Cart::subtotal() > $settings_company->min_amount && $settings_company->min_amount > 0)
                                    <span class="text-pink-500">Gratis</span>
                                @else
                                    S/ {{ number_format($shipping_cost, 2) }}
                                @endif
                            @endif
                        </span>
                    </p>

                    <hr class="mt-4 mb-3">

                    <p class="flex justify-between items-center font-semibold">
                        <span class="text-lg">Total</span>
                        @if ($envio_type == 1 || $shipping_cost == 0)
                            S/ {{ Cart::subtotal() }}
                        @else
                            S/ {{ Cart::subtotal() + $shipping_cost }}
                        @endif

                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mt-4">
                <div class="flex items-center">
                    <x-label value="Nota" />
                </div>
                <textarea class="form-control w-full" style="resize:none" rows="6" wire:model="extra_note"
                    placeholder="Ingrese una descripción adicional a su dirección"></textarea>
                {{-- <x-textarea wire:model="extra_note" placeholder="Ingrese una descripción adicional para sus productos"/> --}}
            </div>

            <div class="sm:hidden md:hidden lg:block w-full text-right">
                <x-button wire.loading.attr="disabled" wire.target="create_order" class="mt-6 mb-4"
                    wire:click="create_order">
                    Continuar con la compra
                </x-button>
            </div>
        </div>
    @else
        <div class="flex flex-col col-span-5 items-center py-4 bg-white shadow-lg px-6 mt-4" style="height: 180px">
            <x-cart />
            <p class="text-lg text-gray-700 mt-4">TU CARRO DE COMPRAS ESTÁ VACÍO</p>
            <x-danger-enlace href="{{ route('welcome') }}" class="mt-4 px-16">
                Ir al inicio
            </x-danger-enlace>
        </div>

    @endif
</div>
