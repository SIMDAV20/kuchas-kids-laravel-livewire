<x-app-layout>

    @livewire('banner-home')

    <div class="container py-8">
        @foreach ($categories as $category)
            @if (count($category->products))
                <section class="mb-6 mx-4">
                    <div class="flex items-center">
                        <h1 class="text-lg uppercase font-semibold text-gray-550">
                            {{ $category->name }}
                        </h1>

                        <a href="{{ route('categories.show', $category) }}"
                            class="text-violet-350 hover:text-violet-400 hover:underline ml-2 font-semibold">Ver más</a>
                    </div>
                    @livewire('category-products', ['category' => $category], key($category->id))
                </section>
            @endif
        @endforeach
    </div>

    {{-- push es con el stack en el app-layout --}}
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
        </script>
    @endpush
</x-app-layout>
