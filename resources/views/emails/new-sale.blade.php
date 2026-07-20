<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nueva Venta</title>

    <style>
        * {
            color: #252528;
        }

        .text-center {
            text-align: center
        }

        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .items-center {
            align-items: center;
        }

        .text-xl {
            font-size: 20px;
        }

        .text-lg {
            font-size: 18px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-sm {
            font-size: 12px;
        }

        .font-semi-bold {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 600;
        }

        p {
            margin-top: 0 !important;
        }

    </style>
</head>

<body>

    @php
        $settings_company = \App\Models\Setting::first();
        $items = json_decode($order->content);
        $envio = json_decode($order->envio);
    @endphp

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <p class="text-xl">
            <strong>Número de orden:</strong>
            <span>{{ $order->id }}<span>
        </p>
        <div class="flex flex-col" style="margin-bottom: 20px">
            <div style="margin-right: 20px">
                <div>
                    <p class="text-lg font-semibold uppercase">
                        Envío
                    </p>
                    @if ($order->envio_type == 1)
                        <p class="text-sm">Los productos deben ser recogidos en tienda</p>
                        <p class="text-sm">Jr. Brigadier Mateo Pumacahua 2541 Lince, Altura cuadra 11 av. César
                            Vallejo</p>
                    @elseif ($order->envio_type == 2)
                        <p class="text-sm">Los productos deben ser enviados a:</p>
                        <p class="text-sm">{{ $envio->address }}</p>
                        <p class="text-sm mb-4">{{ $envio->department }} - {{ $envio->province }} -
                            {{ $envio->district }}</p>
                        <p class="text-sm">Referencia: {{ $envio->references }}</p>
                    @else
                        En coordinar la dirección a través del
                        <br>
                        WhatsApp <a href="https://wa.me/{{ $settings_company->whatsapp }}" class="text-blue-600 hover:text-blue-900"
                            target="_blank">{{ $settings_company->whatsapp }}</a>
                    @endif
                </div>
                <div>
                    <p class="text-lg font-semibold uppercase">Datos del Contacto</p>

                    <table class="text-sm" width="100%">
                        <tr>
                            <td>Persona:</td>
                            @if ($order->other_contact)
                                <td class="font-bold">{{ $order->other_contact }}</td>
                            @else
                                <td class="font-bold">{{ $order->contact }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Teléfono:</td>
                            @if ($order->other_contact)
                                <td class="font-bold">{{ $order->other_phone }}</td>
                            @else
                                <td class="font-bold">{{ $order->phone }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>DNI:</td>
                            @if ($order->other_contact)
                                <td class="font-bold">{{ $order->other_doc_number }}</td>
                            @else
                                <td class="font-bold">{{ $order->doc_number }}</td>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>
            @if (strlen($order->extra_note) > 0)
                <div>
                    <p><strong>Nota extra</strong></p>
                    {{ $order->extra_note }}
                </div>
            @endif
        </div>
        {{-- @if ($order->status != 1) --}}
        <div class="flex flex-col" style="margin-bottom: 20px">
            <div>
                <p class="text-lg font-semibold uppercase mb-3">
                    Medio de pago
                </p>
                <div>
                    @if ($order->payment_method == 1)
                        <p class="text-sm text-red-500 font-bold">IZIPAY</p>
                    @elseif ($order->payment_method == 2)
                        <div class="flex items-center">
                            <p class="text-sm font-bold">YAPE</p>

                            <div class="images ml-4">
                                <img src="{{ @Storage::url($order->payment->images->url) }}"
                                    alt="{{ @Storage::url($order->payment->images->url) }}" width="100" height="50"
                                    class="object-contain" />
                            </div>
                        </div>
                    @else
                        <p class="font-bold text-dark">No se efectuó el pago o el pago no fue completado</p>
                    @endif

                    <div class="mt-3">
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
            </div>
            @if ($order->invoice)
                <div class="w-full">
                    <p class="text-lg font-semibold uppercase mb-3">
                        DETALLE FACTURACIÓN
                    </p>
                    <table class="text-sm" width="100%">
                        <tr>
                            <td>RUC:</td>
                            <td class="font-bold">{{ $order->invoice->ruc }}</td>
                        </tr>
                        <tr>
                            <td>EMPRESA:</td>
                            <td class="font-bold">{{ $order->invoice->company_name }}</td>
                        </tr>
                        <tr>
                            <td>DIRECCIÓN:</td>
                            <td class="font-bold">{{ $order->invoice->company_address }}</td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
        {{-- @endif --}}
        <div style="margin-bottom: 20px">
            <p class="text-xl font-semibold" style="margin-bottom: 20px">Resumen</p>
            <table class="table-auto w-full" style="width: 100%" border="1">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <div style="padding: 15px">
                                    <img width="200" height="200" src="{{ $item->options->image }}"
                                        alt="{{ $item->options->image }}">
                                    <article>
                                        <h1 class="font-bold">{{ $item->name }}</h1>
                                        <div class="flex text-xs">
                                            @isset($item->options->color)
                                                Color: {{ __($item->options->color) }}
                                            @endisset
                                            {{-- @isset($item->options->size)
                                                Talla: {{ $item->options->size }}
                                            @endisset --}}
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

            <div style="margin-top: 20px">
                <p class="flex" style="margin-bottom: 5px">
                    <span style="margin-right: 10px">Subtotal:</span>
                    <span class="font-bold">S/ {{ number_format($order->total - $order->shipping_cost + $order->discount, 2) }}</span>
                </p>
                <p class="flex" style="margin-bottom: 5px">
                    <span style="margin-right: 10px">Envío:</span>
                    <span class="font-bold">{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : 'Gratis' }}</span>
                </p>
                @if ($order->discount > 0)
                    <p class="flex" style="margin-bottom: 5px; color: #16a34a">
                        <span style="margin-right: 10px">Descuento (cupón {{ $order->coupon_code }}):</span>
                        <span class="font-bold">- S/ {{ number_format($order->discount, 2) }}</span>
                    </p>
                @endif
                <p class="flex" style="margin-top: 10px">
                    <span style="margin-right: 10px" class="font-bold text-lg">Total:</span>
                    <span class="text-xl font-bold">S/ {{ number_format($order->total, 2) }}</span>
                </p>
            </div>
        </div>
    </div>

</body>

</html>
