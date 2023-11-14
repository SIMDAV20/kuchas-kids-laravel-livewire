<div>
  <div class="container py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
      {{-- VISTA DESKTOP IMAGES --}}
      <div class="col-span-1 hidden md:block">
        <!-- Swipper -->
        <div wire:init="loadGallery" class="mb-3">
          <div style="--swiper-navigation-color: #BCBBE3;" class="swiper galleryTopSwipper">
            <div class="swiper-wrapper">
              @foreach ($images as $image)
                <li class="swiper-slide">
                  <img src="{{ Storage::url($image) }}" alt="{{ $image }}">
                </li>
              @endforeach
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
          <div thumbsSlider class="galleryThumbsSwipper">
            <div class="swiper-wrapper">
              @foreach ($images as $image)
                <li class="swiper-slide">
                  <img src="{{ Storage::url($image) }}" alt="{{ $image }}">
                </li>
              @endforeach
            </div>
          </div>
        </div>
        {{-- FIN VISTA DESKTOP IMAGES --}}

        <div class="text-gray-550 mb-6 description">
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
          {{-- <div class="flexslider sm:block md:hidden">
            <ul class="slides">
              @foreach ($images as $image)
                <li data-thumb="{{ Storage::url($image->url) }}">
          <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}" />
          </li>
          @endforeach
          </ul>
        </div> --}}
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

          @if (count($colors) > 0)
            <div class="flex mb-2 mt-4">
              @foreach ($colors as $colorh)
                <a style="background-color: {{ $colorh->hex }}"
                  class="w-8 h-8 mr-3 rounded-full cursor-pointer hover:outline
                                    hover:border-white hover:outline-gray-350
                  {{ $colorh->slug == $qs_color ? 'outline outline-gray-350' : '' }}"
                  wire:click="handleSelectColor({{ $colorh->id }})"></a>
              @endforeach
            </div>
          @endif

          @if (count($sizes) > 0)
            <div class="flex mb-2 mt-4">
              @foreach ($sizes as $key => $sizeh)
                <a class="cursor-pointer p-2 mr-2 bg-white {{ @$select_size_id == $sizeh->id ? 'border-2 border-violet-350' : '' }}"
                  wire:click="handleSelectSize({{ $sizeh->id }})">
                  {{ $sizeh->name }}
                </a>
              @endforeach
            </div>
          @endif

          @if ($variant)
            <div>
              <div class="mb-2 mt-4">
                <div class="mb-2 flex">
                  @if ($variant->offer_price > 0)
                    <del class="text-lg font-semibold text-gray-550 mr-3">S/ {{ $variant->price }}</del>
                    <p class="text-2xl font-semibold text-violet-350">S/ {{ $variant->offer_price }}</p>
                  @else
                    <p class="text-2xl font-semibold text-gray-550">S/ {{ $variant->price }}</p>
                  @endif
                </div>
              </div>
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
          @livewire('add-cart-item', ['product' => $product, 'variant' => $variant], key($variant->id))
        </div>
      </div>
    </div>

    {{-- TODO: PENDIENTE --}}
    {{-- @livewire('whatsapp-contact', ['product' => $product, 'color_id' => @$select_color_id, 'size_id' => @$select_size_id], key($product->id)) --}}


    <div class="mt-10">
      {{ $select_size_id }}
      <h2 class="text-violet-350 text-xl font-bold">Productos Relacionados</h2>
      <hr class="my-2">
      @livewire('category-products', ['category' => $product->subcategory->category, 'product' => $product])
    </div>
  </div>
  @push('scripts')
    <script>
      $(document).ready(function() {
        Livewire.on('swiperRefresh', function() {
          const galleryThumbs = new Swiper(".galleryThumbsSwipper", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
          });

          const galleryTop = new Swiper(".galleryTopSwipper", {
            direction: 'horizontal',
            loop: true,
            // spaceBetween: 10,
            navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev",
            },
            thumbs: {
              swiper: galleryThumbs,
            },
          });
        })
      });

      Livewire.on('glider', function(id) {
        // console.log('otra vez');
        Alpine.start();
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
    </script>
  @endpush
</div>
