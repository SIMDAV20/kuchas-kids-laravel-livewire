<div class="container py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
        
        {{-- COLUMNA IZQUIERDA: GALERÍA --}}
        <div>
            <div wire:ignore class="flexslider">
                <ul class="slides">
                    @forelse ($currentImages as $image)
                        <li data-thumb="{{ Storage::url($image->url) }}">
                            <img src="{{ Storage::url($image->url) }}" class="object-cover w-full" />
                        </li>
                    @empty
                        <li>
                            <img src="{{ asset('img/no-image.png') }}" class="object-cover w-full" />
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-8 text-gray-700 bg-white p-6 rounded-lg shadow-sm">
                <h2 class="font-bold text-xl mb-4 text-violet-350">Descripción</h2>
                <div class="prose max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: INFO Y SELECCIÓN --}}
        <div class="flex flex-col gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm sticky top-24">
                
                {{-- Oferta Flash Banner --}}
                @if($flashOffer)
                    <div x-data="timer('{{ $flashOffer->end_date }}')" x-init="start()" class="bg-gradient-to-r from-red-500 to-orange-500 text-white p-4 rounded-lg mb-6 flex items-center justify-between shadow-lg animate-pulse">
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
                    <span class="text-gray-400 text-sm">SKU: {{ $currentVariant ? $currentVariant->sku : '---' }}</span>
                </div>

                {{-- Precios Reactivos --}}
                <div class="mb-6 flex items-baseline gap-3">
                    @if($offerPrice && $offerPrice < $price)
                        <span class="text-4xl font-black text-violet-350">S/ {{ number_format($offerPrice, 2) }}</span>
                        <del class="text-xl text-gray-400">S/ {{ number_format($price, 2) }}</del>
                        <span class="bg-green-100 text-green-600 px-2 py-0.5 rounded text-xs font-bold">
                            -{{ round((1 - ($offerPrice / $price)) * 100) }}%
                        </span>
                    @else
                        <span class="text-4xl font-black text-gray-800">S/ {{ number_format($price, 2) }}</span>
                    @endif
                </div>

                {{-- SELECCIÓN DE ATRIBUTOS --}}
                <div class="space-y-6 mb-8">
                    {{-- Seleccionar Color --}}
                    @if($availableColors->count() > 0)
                        <div>
                            <p class="text-sm font-bold text-gray-700 mb-3 flex justify-between">
                                <span>COLOR:</span>
                                <span class="text-violet-600 uppercase">{{ $availableColors->where('id', $selectedColorId)->first()->value ?? 'Selecciona' }}</span>
                            </p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($availableColors as $color)
                                    <button 
                                        wire:click="selectColor({{ $color->id }})"
                                        class="w-10 h-10 rounded-full border-2 transition-all p-0.5 {{ $selectedColorId == $color->id ? 'border-violet-600 ring-2 ring-violet-200' : 'border-gray-200 hover:border-gray-400' }}"
                                        title="{{ $color->value }}">
                                        <span class="block w-full h-full rounded-full" style="background-color: {{ $color->hex }}"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Seleccionar Talla --}}
                    @if($availableSizes->count() > 0)
                        <div>
                            <p class="text-sm font-bold text-gray-700 mb-3 flex justify-between">
                                <span>TALLA:</span>
                                <span class="text-violet-600 uppercase">{{ $availableSizes->where('id', $selectedSizeId)->first()->value ?? 'Selecciona' }}</span>
                            </p>
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                @foreach($availableSizes as $size)
                                    <button 
                                        wire:click="selectSize({{ $size->id }})"
                                        class="py-2 text-sm font-bold border-2 rounded-md transition-all {{ $selectedSizeId == $size->id ? 'border-violet-600 bg-violet-600 text-white' : 'border-gray-200 text-gray-600 hover:border-gray-400' }}">
                                        {{ $size->value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Stock y Cantidad --}}
                <div class="flex items-center gap-6 mb-8">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">CANTIDAD:</p>
                        <div class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden">
                            <button 
                                wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                                class="px-3 py-1 hover:bg-gray-100 text-gray-600 font-bold transition-colors">-</button>
                            <span class="px-4 py-1 font-bold text-gray-800">{{ $quantity }}</span>
                            <button 
                                wire:click="$set('quantity', {{ min($stock, $quantity + 1) }})"
                                class="px-3 py-1 hover:bg-gray-100 text-gray-600 font-bold transition-colors">+</button>
                        </div>
                    </div>
                    <div>
                        @if($stock > 0)
                            <p class="text-xs text-green-500 font-bold tracking-wider">
                                <i class="fas fa-check-circle mr-1"></i> STOCK DISPONIBLE ({{ $stock }})
                            </p>
                        @else
                            <p class="text-xs text-red-500 font-bold tracking-wider">
                                <i class="fas fa-times-circle mr-1"></i> SIN STOCK
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Botón de Acción --}}
                <div class="space-y-3">
                    <button 
                        @if(!$currentVariant && $product->variants->count() > 0 || $stock <= 0) disabled @endif
                        class="w-full bg-violet-350 hover:bg-violet-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-violet-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        AÑADIR A LA BOLSA
                    </button>
                    
                    <a href="https://wa.me/{{ $settings->whatsapp ?? '' }}?text=Hola, estoy interesado en el producto {{ $product->name }}" 
                       target="_blank"
                       class="flex items-center justify-center gap-2 w-full border-2 border-green-500 text-green-600 py-3 rounded-xl font-bold transition-colors hover:bg-green-50">
                        <i class="fab fa-whatsapp text-xl"></i> CONSULTAR POR WHATSAPP
                    </a>
                </div>

                {{-- Info Extra Envíos --}}
                <div class="mt-8 border-t pt-6 grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-truck text-violet-350 text-xl"></i>
                        <span class="text-[10px] text-gray-500 font-bold uppercase transition">Envío a todo el Perú</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-undo-alt text-violet-350 text-xl"></i>
                        <span class="text-[10px] text-gray-500 font-bold uppercase transition">Cambios garantizados</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Lógica del Timer con Alpine
        function timer(expiry) {
            return {
                expiry: new Date(expiry).getTime(),
                remaining: null,
                days: '00', hours: '00', minutes: '00', seconds: '00',
                start() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    let now = new Date().getTime();
                    let t = this.expiry - now;
                    if (t > 0) {
                        this.days = Math.floor(t / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                        this.hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                        this.minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                        this.seconds = Math.floor((t % (1000 * 60)) / 1000).toString().padStart(2, '0');
                    }
                }
            }
        }

        // Reiniciar FlexSlider cuando cambie la galería (colores)
        Livewire.on('galleryUpdated', () => {
            $('.flexslider').removeData("flexslider");
            $('.flexslider').flexslider({
                animation: "slide",
                controlNav: "thumbnails",
                animationLoop: false,
            });
        });

        $(document).ready(function() {
            $('.flexslider').flexslider({
                animation: "slide",
                controlNav: "thumbnails",
                animationLoop: false,
            });
        });
    </script>
    @endpush
</div>
