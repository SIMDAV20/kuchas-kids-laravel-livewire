<x-app-layout>
  @livewire('product-view', ['product' => $product])

  {{-- @push('scripts')
    <script>
      Livewire.on('glider', function(id) {

        console.log('estoy en afuera');
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
  @endpush --}}

</x-app-layout>
