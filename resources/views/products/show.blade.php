<x-app-layout>
    @livewire('product-detail', ['product' => $product])

    <div class="container pb-16 pt-8">
        <h2 class="text-violet-350 text-2xl font-bold mb-4 uppercase tracking-tighter">Productos Relacionados</h2>
        <hr class="mb-8 border-gray-200">
        
        @livewire('category-products', ['category' => $product->subcategory->category, 'product' => $product])
    </div>

    @push('scripts')
        <script>
            Livewire.on('glider', function(id) {
                Alpine.start();
                new Glider(document.querySelector('.glider-' + id), {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    draggable: true,
                    dots: '.glider-' + id + '~.dots',
                    arrows: {
                        prev: '.glider-' + id + '~.glider-prev',
                        next: '.glider-' + id + '~.glider-next'
                    },
                    responsive: [
                        {
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
</x-app-layout>
