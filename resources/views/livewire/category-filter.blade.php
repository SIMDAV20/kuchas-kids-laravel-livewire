<div>
    <div class="bg-white rounded-lg shadow-lg mb-6">
        <div class="px-6 py-2 flex justify-between items-center">
            <h1 class="font-semibold text-gray-700 uppercase">{{ $category->name }}</h1>
            <div class="hidden md:block grid grid-cols-2 border border-gray-200 divide-x divide-gray-200 text-gray-500">
                <i class="fas fa-border-all p-3 cursor-pointer {{ $view == 'grid' ? 'text-violet-350' : '' }}"
                    wire:click="$set('view', 'grid')"></i>
                <i class="fas fa-th-list p-3 cursor-pointer {{ $view == 'list' ? 'text-violet-350' : '' }}"
                    wire:click="$set('view', 'list')"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <aside>
            <h2 class="font-semibold text-center mb-2 text-gray-550">Subcategorías</h2>
            <ul class="divide-y divide-gray-200 text-gray-550">
                @foreach ($category->subcategories()->orderBy('position', 'ASC')->get() as $subcategory)
                    <li class="py-2 text-sm">
                        {{-- $set('subcategoria', '{{ $subcategory->name }}') se qda almacenado el nombre --}}
                        <a class="cursor-pointer hover:text-violet-350 capitalize {{ $subcategoria == $subcategory->slug ? 'text-violet-350 font-semibold' : '' }}"
                            wire:click="$set('subcategoria', '{{ $subcategory->slug }}')">
                            {{ $subcategory->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            @if (count($category->brands))
                <h2 class="font-semibold text-center mt-4 mb-2">Marcas</h2>
                <ul class="divide-y divide-gray-200">
                    @foreach ($category->brands as $brand)
                        <li class="py-2 text-sm">
                            <a class="cursor-pointer hover:text-violet-350 capitalize {{ $marca == $brand->name ? 'text-violet-350 font-semibold' : '' }}"
                                wire:click="$set('marca', '{{ $brand->name }}')">
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

            @endif
            <x-button class="mt-4 {{ $showButton ? '' : 'hidden' }} bg-violet-350 hover:bg-violet-500"
                wire:click="resetFilters">
                Eliminar filtros
            </x-button>
        </aside>

        <div class="md:col-span-2 lg:col-span-4">
            @if ($view == 'grid')
                {{-- gap-4 es para que tenga una separacion --}}
                <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse ($products as $product)
                        <li class="bg-white rounded-lg shadow">
                            @livewire('product-image', ['product' => $product], key($product->id))
                        </li>
                    @empty
                        <li class="md:col-span-2 lg:col-span-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert">
                                <strong class="font-bold">Upss!</strong>
                                <span class="block sm:inline">No existe ningún producto con este filtro</span>
                            </div>
                        </li>
                    @endforelse
                </ul>
            @else
                <ul>
                    @forelse ($products as $product)
                        <x-product-list :product="$product" />
                    @empty
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Upss!</strong>
                            <span class="block sm:inline">No existe ningún producto con este filtro</span>
                        </div>
                    @endforelse
                </ul>
            @endif

            <div class="mt-4">
                {{ $products->links('pagination-links') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let w = $(window);
        w.on('load', function() {
            if (Modernizr.mq('(max-width: 640px)')) {
                Livewire.on('downPage', (pixeles) => {
                    $('html,body').animate({
                        scrollTop: w.scrollTop() + pixeles
                    });
                })
            } else if (Modernizr.mq('(max-width: 960px)')) {
                console.log('Es una pantalla poco estrecha');
            } else {
                console.log('Es una pantalla normal');
            }
        })

        Livewire.on('gotoTop', () => {
            window.scrollTo({
                top: 500,
                left: 15,
                behaviour: 'smooth'
            })
        })
    </script>
@endpush
