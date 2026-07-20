<div>
    @php

        // dd(base_path('vendor/autoload.php'));
        // require_once __DIR__ . '/vendor/autoload.php';
        require base_path('/vendor/autoload.php');
        /**
         * Define configuration
         */

        /* Username, password and endpoint used for server to server web-service calls */
        Lyra\Client::setDefaultUsername(config('services.izipay.code_user'));
        Lyra\Client::setDefaultPassword(config('services.izipay.password'));
        // prod prodpassword_U3AmZdtfezRmRhLEdUqxKnW4TKfsYHetDDanD5RW37Yh2
        Lyra\Client::setDefaultEndpoint('https://api.micuentaweb.pe');

        Lyra\Client::setDefaultPublicKey(config('services.izipay.key'));
        Lyra\Client::setDefaultSHA256Key(config('services.izipay.hash'));

        // NDg3MTY5NDg6dGVzdHBhc3N3b3JkX3pEUnlLMnpYTTlERkVGTkdVUFAwUDRvVXdVVEJKS21OdWM0ajlSYnc4SURmZg==

        $client = new Lyra\Client();

        /**
         * I create a formToken
         */
        $store = [
            'amount' => $order->total * 100,
            'currency' => 'PEN',
            'orderId' => uniqid($order->id),
            'customer' => [
                'email' => auth()->user()->email,
            ],
        ];
        $response = $client->post('V4/Charge/CreatePayment', $store);

        /* I check if there are some errors */
        if ($response['status'] != 'SUCCESS') {
            /* an error occurs, I throw an exception */
            display_error($response);
            $error = $response['answer'];
            throw new Exception('error ' . $error['errorCode'] . ': ' . $error['errorMessage']);
        }

        /* everything is fine, I extract the formToken */
        $formToken = $response['answer']['formToken'];
    @endphp


    @push('izipay')
        <script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
            kr-public-key="{{ config('services.izipay.key') }}"
            kr-post-url-success="{{ route('orders.izipay', ['order_id' => $order]) }}" kr-language="es-ES"></script>

        <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic-reset.css">
        <script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic.js"></script>
    @endpush

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-5 gap-6 container py-8">
        <div class="order-2 lg:order-1 xl:col-span-3">
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 mb-6">
                <p class="text-gray-700 uppercase flex xs:flex-col">
                    <span class="font-semibold">Número de orden:</span>
                    <span class="ml-1">{{ $order->id }}</span>
                </p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-lg font-semibold uppercase">
                            Envío
                        </p>
                        @if ($order->envio_type == 1)
                            <p class="text-sm">Los productos deben ser recogidos en tienda</p>
                            <p class="text-sm">Jr. Brigadier Mateo Pumacahua 2541 Lince, Altura cuadra 11 av.
                                César Vallejo</p>
                        @elseif ($order->envio_type == 2)
                            <p class="text-sm">Los productos deben ser enviados a:</p>
                            <p class="text-sm">{{ $envio->address }}</p>
                            <p class="text-sm">{{ $envio->department }} - {{ $envio->province }} -
                                {{ $envio->district }}</p>
                        @else
                            Por Coordinar
                        @endif
                    </div>
                    <div>
                        <p class="text-lg font-semibold  uppercase">Datos del Contacto</p>

                        @if ($order->other_contact == null)
                            <p class="text-sm mb-2">Persona que recibirá el producto: {{ $order->contact }}</p>
                            <div class="flex">
                                <p class="text-sm mr-3">Teléfono: {{ $order->phone }}</p>
                                <p class="text-sm">DNI: {{ $order->doc_number }}</p>
                            </div>
                        @else
                            <p class="text-sm">Persona que recibirá el producto: {{ $order->other_contact }}
                            </p>
                            <div class="flex">
                                <p class="text-sm mr-3">Teléfono: {{ $order->other_phone }}</p>
                                <p class="text-sm">DNI: {{ $order->other_doc_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 text-gray-700">
                <p class="text-xl font-semibold mb-4">Resumen</p>
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
                                    <div class="flex mb-2">
                                        <img class="h-16 w-16 object-contain mr-4" src="{{ $item->options->image }}"
                                            alt="">
                                        <article class="flex justify-center flex-col">
                                            <h1 class="font-bold">{{ $item->name }}</h1>
                                            <div class="flex text-xs">
                                                @isset($item->options->color)
                                                    Color: {{ __($item->options->color) }}
                                                @endisset
                                                @isset($item->options->size)
                                                    Talla: {{ $item->options->size }}
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
            </div>
        </div>
        <div class="order-1 lg:order-2 xl:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-3">
                <div class="flex justify-between items-center">
                    <img class="h-12 hidden md:flex" src="{{ asset('/img/tipo_de_tarjetas.jpg') }}" alt="">
                    <div class="text-gray-700 content-prices">
                        <p class="flex items-center justify-between text-sm font-semibold mb-1">
                            <span class="mr-1">Subtotal:</span> <span>S/
                                {{ number_format($order->total - $order->shipping_cost + $order->discount, 2) }}</span>
                        </p>
                        <p class="flex items-center justify-between text-sm font-semibold mb-1">
                            <span class="mr-1">Envío:</span>
                            <span>{{ $order->shipping_cost > 0 ? 'S/ ' . $order->shipping_cost : ' Gratis ' }}</span>
                        </p>
                        @if ($order->discount > 0)
                            <p class="flex items-center justify-between text-sm font-semibold mb-1 text-green-600">
                                <span class="mr-1">Descuento (cupón):</span> <span>- S/
                                    {{ number_format($order->discount, 2) }}</span>
                            </p>
                        @endif
                        <p class="flex items-center justify-between text-lg font-semibold uppercase">
                            <span class="mr-1">Pago:</span> <span>S/ {{ $order->total }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <p class="text-gray-700 uppercase text-lg font-semibold mb-3">
                    {{-- Selecciona el método de pago --}}
                    elige un método de pago
                </p>
                <hr class="mb-4">
                <ul x-data="{ payment_method: 0 }">
                    <li class="flex flex-col">
                        <div class="flex justify-between items-center mb-3">
                            <label for="izipay">
                                <div style="background-color: #FF4240" class="p-3">
                                    <img src="{{ asset('img/Izipaylogo.png') }}" width="120" alt="Izipaylogo"
                                        class="izipay-logo">
                                </div>
                            </label>
                            <input x-model="payment_method" id="izipay" type="radio" value="1"
                                name="payment_method" class="text-gray-600 mr-2">
                        </div>
                        <div class="m-auto" x-show="payment_method == 1" x-transition>
                            <div id="paymentForm" class="kr-embedded" kr-form-token="{{ $formToken }}">

                                <div class="kr-pan"></div>
                                <div class="kr-expiry"></div>
                                <div class="kr-security-code"></div>

                                <button class="kr-payment-button"></button>

                                <div class="kr-form-error"></div>
                            </div>
                        </div>
                    </li>
                    <hr class="mb-4">

                    <li class="mb-3">

                        <div class="flex justify-between items-center mb-3">
                            <label for="yape">
                                <span class="text-purple-700 font-bold">Yape</span> - Pago por adelantado
                            </label>
                            <input x-model="payment_method" id="yape" type="radio" value="2"
                                name="payment_method" class="text-gray-600 mr-2">
                        </div>

                        <div x-show="payment_method == 2" x-transition>
                            <p class="mb-2 text-sm">
                                Escanea del código QR desde la app Yape o yapea al
                                <a href="https://wa.me/{{ $settings_company->whatsapp }}" class="text-blue-600 hover:text-blue-400 mb-4" target="_blank">{{ $settings_company->whatsapp }}</a>
                            </p>
                            <p class="mb-2 text-sm">
                                Ingresa el monto total que aparece en el carrito de compras, incluyendo el costo de
                                delivery
                                ( si existiera ).
                            </p>
                            <p class="mb-2 text-sm text-pink-600 text-bold">
                                Como mensaje en Yape, INGRESA TU NÚMERO DE PEDIDO "{{ $order->id }}"
                                para poder indentificarte
                            </p>
                            <div class="flex justify-center flex-wrap">
                                <img src="{{ asset('img/yape.png') }}" class="w-40 h-40 object-cover text-center"
                                    alt="">
                                <img src="{{ asset('img/qr-yape.png') }}" class="w-40 h-40 object-cover text-center"
                                    alt="">
                            </div>


                            <div x-data="yapeUpload()" class="my-2">
                                <input
                                    type="file"
                                    wire:model.lazy="photo"
                                    accept="image/*"
                                    id="{{ $rand }}"
                                    x-ref="fileInput"
                                    @change="onFileChange($event)"
                                    wire:loading.attr="disabled"
                                    wire:target="saveYape, photo"
                                    class="hidden"
                                />

                                <div
                                    @click="$refs.fileInput.click()"
                                    @dragover.prevent="dragging = true"
                                    @dragleave.prevent="dragging = false"
                                    @drop.prevent="handleDrop($event)"
                                    :class="dragging ? 'border-violet-500 bg-violet-50' : 'border-gray-300'"
                                    class="relative flex flex-col items-center justify-center gap-1 border-2 border-dashed rounded-lg py-6 px-4 cursor-pointer transition-colors hover:border-violet-400"
                                >
                                    <div x-show="!preview" class="flex flex-col items-center text-center">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm font-medium text-gray-600">
                                            Arrastra tu comprobante aquí o <span class="text-violet-600 underline">haz clic para elegir</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">PNG o JPG, máx. 2MB</p>
                                    </div>

                                    <div x-show="preview" class="relative w-full flex flex-col items-center">
                                        <img :src="preview" class="max-h-48 rounded-md object-contain shadow">
                                        <p class="text-xs text-gray-500 mt-2 truncate max-w-full" x-text="fileName"></p>
                                        <button
                                            type="button"
                                            @click.stop="clearFile()"
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow"
                                        >
                                            &times;
                                        </button>
                                    </div>

                                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-lg">
                                        <span class="text-sm text-violet-600 font-semibold">Subiendo...</span>
                                    </div>
                                </div>
                            </div>
                            <x-input-error for="photo" class="mb-2" />

                            <x-button color="blue" class="w-full" wire:loading.attr="disabled"
                                wire:target="saveYape, photo" wire:click="saveYape">
                                Adjuntar Foto
                            </x-button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('yapeUpload', () => ({
                dragging: false,
                preview: null,
                fileName: '',

                onFileChange(event) {
                    this.setPreview(event.target.files[0]);
                },

                handleDrop(event) {
                    this.dragging = false;

                    const file = event.dataTransfer.files[0];
                    if (!file) return;

                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    this.$refs.fileInput.files = transfer.files;
                    this.$refs.fileInput.dispatchEvent(new Event('change'));

                    this.setPreview(file);
                },

                setPreview(file) {
                    if (!file) return;

                    this.fileName = file.name;

                    const reader = new FileReader();
                    reader.onload = e => { this.preview = e.target.result; };
                    reader.readAsDataURL(file);
                },

                clearFile() {
                    this.preview = null;
                    this.fileName = '';
                    this.$refs.fileInput.value = '';
                    this.$wire.set('photo', null);
                },
            }));
        });
    </script>
@endpush
