<x-app-layout>
    <div class="container py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            {{-- VISTA DESKTOP --}}
            <div class="col-span-1 hidden md:block">
                <div class="flexslider">
                    <ul class="slides">
                        @if ($product->color_product->count() > 0)
                        @foreach ($product->color_product->where('color_id', $color->id) as $key => $p_color_prod)
                        @foreach ($p_color_prod->images as $image)
                        <li data-thumb="{{ Storage::url($image->url) }}">
                            <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}" />
                        </li>
                        @endforeach
                        @endforeach

                        {{-- @elseif ($product->product_size->count() > 0)
                        @foreach ($product->product_size>where('size_id', $size->id) as $key => $p_prod_size)
                        @foreach ($p_prod_size->images as $image)
                        <li data-thumb="{{ Storage::url($image->url) }}">
                            <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}" />
                        </li>
                        @endforeach
                        @endforeach --}}


                        @else
                        @foreach ($product->images as $image)
                        <li data-thumb="{{ Storage::url($image->url) }}">
                            <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}" />
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                {{-- FIN VISTA DESKTOP --}}

                <div class="-mt-10 text-gray-550 mb-6 description">
                    <h2 class="font-bold text-lg mb-3">Descripción</h2>
                    {!! $product->description !!}
                </div>

                @if (@$product->video)
                <div>
                    <h2 class="font-bold text-lg mb-3 text-gray-550">Video</h2>
                    <div class="video-responsive">
                        <iframe width="560" height="315" src="{{ $product->video }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                </div>
                @endif
            </div>

            {{-- LADO DERECHO --}}
            <div class="h-full">
                <div class="sticky" style="top: 80px">
                    <h1 class="text-4xl font-bold text-violet-350">{{ $product->name }} </h1>

                    {{-- VISTA MOBIL --}}
                    <div class="flexslider sm:block md:hidden">
                        <ul class="slides">
                            @if (count($product->color_product))
                            @foreach ($product->color_product->where('color_id', $color->id) as $key => $p_color_prod)
                            @foreach ($p_color_prod->images as $image)
                            <li data-thumb="{{ Storage::url($image->url) }}">
                                <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}" />
                            </li>
                            @endforeach
                            @endforeach
                            @else
                            @foreach ($product->images as $image)
                            <li data-thumb="{{ Storage::url($image->url) }}">
                                <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}" />
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    {{-- FIN VISTA MOBIL --}}

                    <hr class="my-3">

                    {{-- Si tiene marca --}}
                    @if ($product->brand)
                    <div class="flex mb-2">
                        <p class="text-violet-350">
                            <strong>Marca:</strong>
                            <span>{{ $product->brand->name }}</span>
                        </p>
                    </div>
                    @endif
                    {{-- Si tiene edad --}}
                    @if ($product->age)
                    <div class="flex mb-2">
                        <p class="text-violet-350">
                            <strong>Edades:</strong>
                            <span>{{ $product->age }}</span>
                        </p>
                    </div>
                    @endif

                    {{-- @if ($product->offer_price > 0 && \Carbon\Carbon::parse($product->offer_date)->format('Y-m-d')
                    >= \Carbon\Carbon::now()->format('Y-m-d') && $product->offer_date !== null)
                    <div class="flex">
                        <del class="text-lg font-semibold text-gray-350 mr-3">S/ {{ $product->price }}</del>
                        <p class="text-2xl font-semibold text-violet-350">S/ {{ $product->offer_price }}</p>
                    </div>
                    @elseif ($product->offer_price > 0 && $product->offer_date == null)
                    <div class="flex">
                        <del class="text-lg font-semibold text-gray-350 mr-3">S/ {{ $product->price }}</del>
                        <p class="text-2xl font-semibold text-violet-350">S/ {{ $product->offer_price }}</p>
                    </div>
                    @endif --}}

                    @if (count($product->sizes) > 0)
                    @livewire('product-size', ['product' => $product], key($product->id))
                    @else
                    @if ($product->offer_price)
                    <div class="flex items-center">
                        <del class="text-2xl text-gray-500 font-bold mr-2">S/
                            {{ $product->price }}</del>
                        <p class="text-2xl text-violet-350 font-bold">S/ {{ $product->offer_price }}</p>
                    </div>
                    @else
                    <p class="text-2xl font-bold text-violet-350">S/ {{ $product->price }}</p>
                    @endif
                    {{-- <p class="text-2xl font-semibold text-gray-350 my-4">S/ {{ $product->price }}</p> --}}
                    @endif

                    {{-- TODO: terminar esta sección MARCAR LA FECHA DE OFERTA HASTA --}}
                    {{-- @if ($showOfferDate && $product->offer_date !== null)
                    <p class="mt-3">
                        <strong>Oferta hasta: </strong>
                        @if ($sameDay)
                        HOY
                        @else
                        {{ \Carbon\Carbon::parse($product->offer_date)->format('d/m/Y') }}
                        @endif
                    </p>
                    @endif --}}

                    {{-- MOSTRAR LAS OPCIONES DE COLORES --}}
                    @if (count($product->colors) > 0)
                    <div class="flex mb-2 mt-4">
                        @foreach ($product->colors as $colorh)
                        <a style="background-color: {{ $colorh->hex }}" class="w-8 h-8 mr-3 rounded-full cursor-pointer
                                        hover:border-white hover:outline-gray
                                        {{ $colorh->slug == $color->slug ? 'outline-gray' : '' }}
                                        "
                            href="{{ route('products.show', ['product' => $product, 'color' => $colorh->slug]) }}"></a>
                        @endforeach
                    </div>
                    @endif

                    {{-- MOSTRAR LA INFO DE ENTREGAS --}}
                    <div class="bg-white rounded-lg shadow-lg my-6">
                        <div class="p-4 flex items-center">
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-violet-350">
                                <i class="fas fa-truck text-xl text-white"></i>
                            </span>
                            <div class="ml-4">
                                <p class="text-lg text-semibold text-violet-350">Hacemos envíos a todo el Perú</p>
                                {{-- <p>Recíbelo el {{ Date::now()->addDay(1)->locale('es')->format('l j F') }}</p> --}}
                                <p class="text-gray-550">Recíbelo de 1 a 3 días útiles</p>
                            </div>
                        </div>
                    </div>

                    {{-- INICIO DEL BOTON DE AGREGAR AL CARRITO SEGUN SUS VARIANTES --}}
                    @if (count($product->sizes) > 0)
                    @livewire('add-cart-item-size', ['product' => $product])
                    @elseif (count($product->colors) > 0)
                    @livewire('add-cart-item-color', ['product' => $product, 'color' => $color])
                    @else
                    @livewire('add-cart-item', ['product' => $product])
                    @endif
                </div>
            </div>
        </div>

        @livewire('whatsapp-contact', ['product' => $product, 'color' => @$color, 'size' =>
        @$product->product_size->first()->size->name], key($product->id))

        {{-- PARA VISTA MOBIL --}}
        <div class="md:hidden sm:block mt-16">
            <div class="-mt-10 text-gray-700 mb-6 description">
                <h2 class="font-bold text-lg mb-3">Descripción</h2>
                {!! $product->description !!}
            </div>

            @if (@$product->video)
            <div>
                <h2 class="font-bold text-lg mb-3">Video</h2>
                <div class="video-responsive">
                    <iframe width="560" height="315" src="{{ $product->video }}" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
            @endif
        </div>

        <div class="sm:mt-10 md:mt-56">
            <h2 class="text-violet-350 text-xl font-bold">Productos Relacionados</h2>
            <hr class="my-2">
            @livewire('category-products', ['category' => $product->subcategory->category, 'product' => $product])
        </div>
    </div>

    @push('scripts')
    <script>
        Livewire.on('glider', function(id) {
                // Alpine.start();
                new Glider(document.querySelector('.glider-' + id), {
                    // ~ es para llamar a las tags hermanas
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    // draggable: true,
                    dots: '.glider-' + id + '~.dots',
                    arrows: {
                        prev: '.glider-' + id + '~.glider-prev',
                        next: '.glider-' + id + '~.glider-next'
                    },
                    responsive: [{
                            breakpoint: 640,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3.5,
                                slidesToScroll: 3,
                            }
                        },
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 4.5,
                                slidesToScroll: 4,
                            }
                        },
                        {
                            breakpoint: 1280,
                            settings: {
                                slidesToShow: 5.5,
                                slidesToScroll: 5,
                            }
                        },
                    ]
                });
            });

            $(document).ready(function() {
                $('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails",
                    animationLoop: false,
                    // itemMargin: 5
                });
            });
    </script>
    @endpush
</x-app-layout>