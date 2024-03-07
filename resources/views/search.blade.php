<x-app-layout>
  <div class="container py-8 min-h-screen">
    <ul>
      @forelse ($products as $product)
        <x-product-list :product="$product" />
      @empty
        <li class="bg-white font-semibold rounded-lg shadow-2xl md:my-14">
          <div class="p-4 text-violet-350">
            Ningún producto coincide con esos parámetros
          </div>
        </li>

        <div class="mt-16">
          <h2 class="text-violet-350 text-xl font-bold">Productos de su interés</h2>
          <hr class="my-2">
          @livewire('category-products', ['category' => null])
        </div>
      @endforelse
    </ul>

    <div class="mt-4">
      {{-- {{ $products->appends(['name' => $name])->links() }} --}}
      {{-- {{ $products->links() }} --}}
      {{ $products->withQueryString()->links() }}
    </div>
  </div>

  @push('scripts')
    <script>
      Livewire.on('glider', function(id) {
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
