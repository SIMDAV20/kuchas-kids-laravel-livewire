<div class="container py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">

        {{-- ============================================================
             COLUMNA IZQUIERDA: GALERÍA CON SWIPER
             - Se inicializa vía evento `swiperRefresh` emitido por Livewire
             - Se reinicia cada vez que el usuario selecciona un atributo
             - wire:ignore: Livewire no toca el DOM del Swiper tras init
             ============================================================ --}}
        <div>
            <div wire:init="updateGallery" wire:ignore>

                {{-- Swiper principal: imagen grande --}}
                <div style="--swiper-navigation-color: #BCBBE3;"
                     class="swiper galleryTopSwipper rounded-xl overflow-hidden mb-3">
                    <div class="swiper-wrapper">
                        @forelse($currentImages as $image)
                            <div class="swiper-slide">
                                <img src="{{ Storage::url($image->url) }}"
                                     class="w-full object-cover aspect-square"
                                     alt="{{ $product->name }}">
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <img src="{{ asset('img/no-image.png') }}"
                                     class="w-full object-cover aspect-square"
                                     alt="Sin imagen">
                            </div>
                        @endforelse
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                {{-- Swiper thumbnails: miniaturas clicables --}}
                <div thumbsSlider class="swiper galleryThumbsSwipper">
                    <div class="swiper-wrapper">
                        @forelse($currentImages as $image)
                            <div class="swiper-slide cursor-pointer rounded-lg overflow-hidden border-2 border-transparent">
                                <img src="{{ Storage::url($image->url) }}"
                                     class="w-full object-cover aspect-square"
                                     alt="{{ $product->name }}">
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <img src="{{ asset('img/no-image.png') }}" class="w-full object-cover aspect-square">
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Descripción del producto --}}
            <div class="mt-8 text-gray-700 bg-white p-6 rounded-lg shadow-sm">
                <h2 class="font-bold text-xl mb-4 text-violet-350">Descripción</h2>
                <div class="prose max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        {{-- ============================================================
             COLUMNA DERECHA: INFO, ATRIBUTOS Y COMPRA
             - El precio reacciona a la variante encontrada
             - Si no hay variante para la combinación → precio deshabilitado
             - Los atributos filtran tallas disponibles según color elegido
             ============================================================ --}}
        <div class="flex flex-col gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm sticky top-24">

                {{-- ── Flash Offer Banner ──────────────────────────────
                     Solo visible si hay oferta activa (variante o producto)
                ──────────────────────────────────────────────────────── --}}
                @if($flashOffer)
                    <div x-data="timer('{{ $flashOffer->end_date }}')" x-init="start()"
                         class="bg-gradient-to-r from-red-500 to-orange-500 text-white p-4 rounded-lg mb-6 flex items-center justify-between shadow-lg animate-pulse">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-bolt text-2xl"></i>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider">Oferta Flash</p>
                                <p class="text-lg font-black">{{ $flashOffer->name }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 text-center">
                            <div class="bg-white/20 p-2 rounded min-w-[45px]">
                                <span x-text="days" class="block text-xl font-bold"></span>
                                <span class="text-[10px] uppercase">Días</span>
                            </div>
                            <div class="bg-white/20 p-2 rounded min-w-[45px]">
                                <span x-text="hours" class="block text-xl font-bold"></span>
                                <span class="text-[10px] uppercase">Hrs</span>
                            </div>
                            <div class="bg-white/20 p-2 rounded min-w-[45px]">
                                <span x-text="minutes" class="block text-xl font-bold"></span>
                                <span class="text-[10px] uppercase">Min</span>
                            </div>
                            <div class="bg-white/20 p-2 rounded min-w-[45px]">
                                <span x-text="seconds" class="block text-xl font-bold"></span>
                                <span class="text-[10px] uppercase">Seg</span>
                            </div>
                        </div>
                    </div>
                @endif

                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>

                <div class="flex items-center gap-4 mb-4">
                    @if($product->brand)
                        <span class="bg-violet-100 text-violet-600 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $product->brand->name }}
                        </span>
                    @endif
                    <span class="text-gray-400 text-sm">
                        SKU: {{ $currentVariant ? $currentVariant->sku : '---' }}
                    </span>
                </div>

                {{-- ── Precio ──────────────────────────────────────────
                     Estados:
                     a) Variante encontrada + oferta  → precio tachado + precio oferta
                     b) Variante encontrada sin oferta → precio normal
                     c) Sin variante (combinación inválida) → grayed + "No disponible"
                ──────────────────────────────────────────────────────── --}}
                <div class="mb-6">
                    @if($currentVariant)
                        {{-- (a/b) Variante encontrada --}}
                        <div class="flex items-baseline gap-3">
                            @if($offerPrice && $offerPrice < $price)
                                {{-- Precio con descuento --}}
                                <span class="text-4xl font-black text-violet-350">
                                    S/ {{ number_format($offerPrice, 2) }}
                                </span>
                                <del class="text-xl text-gray-400">S/ {{ number_format($price, 2) }}</del>
                                <span class="bg-green-100 text-green-600 px-2 py-0.5 rounded text-xs font-bold">
                                    -{{ round((1 - ($offerPrice / $price)) * 100) }}%
                                </span>
                            @else
                                {{-- Precio regular --}}
                                <span class="text-4xl font-black text-gray-800">
                                    S/ {{ number_format($price, 2) }}
                                </span>
                            @endif
                        </div>
                    @else
                        {{-- (c) Combinación inválida — precio deshabilitado visualmente --}}
                        <div class="flex items-baseline gap-3 opacity-40 select-none">
                            <span class="text-4xl font-black text-gray-400">S/ ---</span>
                        </div>
                        <p class="text-xs text-red-500 font-bold mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Combinación no disponible. Selecciona otra opción.
                        </p>
                    @endif
                </div>

                {{-- ── Selección de Atributos ──────────────────────────
                     UX flow:
                     1. Usuario elige COLOR → Livewire filtra tallas disponibles
                        para ese color, reinicia galería con imágenes de la variante
                     2. Usuario elige TALLA → Livewire resuelve variante exacta,
                        actualiza precio y galería
                     3. Si ninguna variante coincide → precio deshabilitado (ver arriba)
                ──────────────────────────────────────────────────────── --}}
                <div class="space-y-6 mb-8">

                    {{-- Selector de Color --}}
                    @if($availableColors->count() > 0)
                        <div>
                            <p class="text-sm font-bold text-gray-700 mb-3 flex justify-between">
                                <span>COLOR:</span>
                                {{-- Muestra el color activo o invita a seleccionar --}}
                                <span class="text-violet-600 uppercase">
                                    {{ $availableColors->where('id', $selectedColorId)->first()->value ?? 'Selecciona' }}
                                </span>
                            </p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($availableColors as $color)
                                    {{-- Cada botón envía el ID al método selectColor()
                                         que actualiza selectedColorId, busca variante y refresca galería --}}
                                    <button
                                        wire:click="selectColor({{ $color->id }})"
                                        class="w-10 h-10 rounded-full border-2 transition-all p-0.5
                                               {{ $selectedColorId == $color->id
                                                    ? 'border-violet-600 ring-2 ring-violet-200'
                                                    : 'border-gray-200 hover:border-gray-400' }}"
                                        title="{{ $color->value }}">
                                        <span class="block w-full h-full rounded-full"
                                              style="background-color: {{ $color->hex }}"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Selector de Talla --}}
                    @if($availableSizes->count() > 0)
                        <div>
                            <p class="text-sm font-bold text-gray-700 mb-3 flex justify-between">
                                <span>TALLA:</span>
                                {{-- Muestra la talla activa o invita a seleccionar --}}
                                <span class="text-violet-600 uppercase">
                                    {{ $availableSizes->where('id', $selectedSizeId)->first()->value ?? 'Selecciona' }}
                                </span>
                            </p>
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                @foreach($availableSizes as $size)
                                    {{-- Tallas filtradas según color elegido (si aplica).
                                         Si la talla no tiene stock en la variante,
                                         podría marcarse disabled (extensión futura) --}}
                                    <button
                                        wire:click="selectSize({{ $size->id }})"
                                        class="py-2 text-sm font-bold border-2 rounded-md transition-all
                                               {{ $selectedSizeId == $size->id
                                                    ? 'border-violet-600 bg-violet-600 text-white'
                                                    : 'border-gray-200 text-gray-600 hover:border-gray-400' }}">
                                        {{ $size->value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ── Añadir al Carrito ───────────────────────────────
                     Se pasa variantId para que add-cart-item sepa qué variante
                     agregar. Cuando $currentVariant es null, el componente
                     debería deshabilitar el botón internamente.
                ──────────────────────────────────────────────────────── --}}
                <div class="mb-4">
                    @livewire('add-cart-item', [
                        'product'   => $product,
                        'variantId' => $currentVariant ? $currentVariant->id : null,
                    ], key('add-cart-item-' . ($currentVariant ? $currentVariant->id : 'base')))
                </div>

                {{-- WhatsApp CTA --}}
                <a href="https://wa.me/{{ $settings->whatsapp ?? '' }}?text=Hola, estoy interesado en el producto {{ $product->name }}"
                   target="_blank"
                   class="flex items-center justify-center gap-2 w-full border-2 border-green-500 text-green-600 py-3 rounded-xl font-bold transition-colors hover:bg-green-50">
                    <i class="fab fa-whatsapp text-xl"></i> CONSULTAR POR WHATSAPP
                </a>

                {{-- Info envíos --}}
                <div class="mt-8 border-t pt-6 grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-truck text-violet-350 text-xl"></i>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-500 font-bold uppercase">Envío a todo el Perú</span>
                            <span class="text-[9px] text-violet-600 font-bold uppercase leading-tight mt-0.5">Recíbelo de 1 a 3 días útiles</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-undo-alt text-violet-350 text-xl"></i>
                        <span class="text-[10px] text-gray-500 font-bold uppercase">Garantía garantizada</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ── Flash Offer countdown timer (Alpine inline function) ──────
        function timer(expiry) {
            return {
                expiry: new Date(expiry).getTime(),
                days: '00', hours: '00', minutes: '00', seconds: '00',
                start() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    let t = this.expiry - new Date().getTime();
                    if (t > 0) {
                        this.days    = Math.floor(t / 86400000).toString().padStart(2, '0');
                        this.hours   = Math.floor((t % 86400000) / 3600000).toString().padStart(2, '0');
                        this.minutes = Math.floor((t % 3600000) / 60000).toString().padStart(2, '0');
                        this.seconds = Math.floor((t % 60000) / 1000).toString().padStart(2, '0');
                    }
                }
            }
        }

        // ── Swiper gallery init ───────────────────────────────────────
        // Pattern from docs/product-view.md:
        // Livewire emits `swiperRefresh` after any gallery change
        // (initial load via wire:init, color/size selection).
        // We destroy previous instances before re-creating to avoid
        // duplicate event bindings on the same DOM elements.
        let galleryTop   = null;
        let galleryThumbs = null;

        $(document).ready(function () {
            Livewire.on('swiperRefresh', function () {
                // Destroy previous instances if they exist
                if (galleryTop)   { galleryTop.destroy(true, true);   galleryTop   = null; }
                if (galleryThumbs){ galleryThumbs.destroy(true, true); galleryThumbs = null; }

                // Thumbnails strip — must be created first so main swiper can link to it
                galleryThumbs = new Swiper('.galleryThumbsSwipper', {
                    spaceBetween: 8,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                });

                // Main viewer linked to thumbnails
                galleryTop = new Swiper('.galleryTopSwipper', {
                    direction: 'horizontal',
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    thumbs: {
                        swiper: galleryThumbs, // clicking a thumb jumps main swiper
                    },
                });
            });
        });
    </script>
    @endpush
</div>
