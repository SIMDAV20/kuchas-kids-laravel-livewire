<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-600">
                Lista de Productos
            </h2>
            <x-button-enlace href="{{ route('admin.products.create') }}" class="ml-auto">
                Agregar Producto
            </x-button-enlace>
        </div>
    </x-slot>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-8 text-gray-900">
        <x-table-fixed-header maxHeight="75vh">
            {{-- Barra de Herramientas y Búsqueda --}}
            <div class="px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex items-center gap-4">
                    {{-- Buscador --}}
                    <div class="flex-1 relative">
                        <x-input type="text" wire:model.debounce.500ms="search"
                            placeholder="Buscar por nombre, SKU o categoría..." class="w-full pl-10" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>

                    {{-- Acciones Masivas --}}
                    @if (count($selectedProducts))
                        <div class="flex items-center gap-2 animate-fade-in">
                            <span class="text-sm font-medium text-gray-600 mr-2">
                                {{ count($selectedProducts) }} seleccionados:
                            </span>
                            <x-button wire:click="publishSelected" class="bg-green-600 hover:bg-green-700 text-[10px]">
                                <i class="fas fa-eye mr-1"></i> Publicar
                            </x-button>
                            <x-button wire:click="draftSelected" class="bg-gray-600 hover:bg-gray-700 text-[10px]">
                                <i class="fas fa-eye-slash mr-1"></i> Borrador
                            </x-button>
                            <x-button wire:click="deleteSelected" class="bg-red-600 hover:bg-red-700 text-[10px]"
                                onclick="confirm('¿Estás seguro de eliminar los productos seleccionados?') || event.stopImmediatePropagation()">
                                <i class="fas fa-trash mr-1"></i> Eliminar
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>

            @if ($products->count())
                <table class="min-w-full divide-y divide-gray-200" x-data="{ selectedRows: @entangle('selectedProducts') }">
                    <thead class="bg-gray-50 sticky top-0 z-20 shadow-sm shadow-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" wire:model="selectAll" class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                            </th>
                            <th class="w-10"></th> {{-- Toggle --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Producto
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Cat / Subcat
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Oferta Flash
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Stock Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr x-data="{ open: false }" class="hover:bg-gray-50 transition-colors border-b">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $product->id }}" wire:model="selectedProducts" class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                                </td>
                                
                                {{-- Botón de Toggle --}}
                                <td class="px-2 py-4">
                                    @if ($product->variants->count() > 0)
                                        <button @click="open = !open" class="text-gray-400 hover:text-violet-600 transition-colors">
                                            <i class="fas" :class="open ? 'fa-minus-circle' : 'fa-plus-circle'"></i>
                                        </button>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-lg object-cover shadow-sm"
                                                src="{{ $product->images_relations->count() ? Storage::url($product->images_relations->first()->url) : asset('img/default.jpg') }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                            <div class="text-[10px] text-gray-400">ID: #{{ $product->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-left">
                                    <div class="text-xs text-gray-600">
                                        <span class="font-bold text-gray-800">{{ $product->subcategory->category->name }}</span><br>
                                        {{ $product->subcategory->name }}
                                    </div>
                                </td>

                                {{-- Oferta Flash --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $hasActiveFlash = $product->flashOffer?->status || $product->variants->filter(fn($v) => $v->flashOffer?->status)->count() > 0;
                                    @endphp
                                    @if($hasActiveFlash)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800 animate-pulse">
                                            <i class="fas fa-bolt mr-1"></i> ACTIVA
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">---</span>
                                    @endif
                                </td>

                                {{-- Stock --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-black {{ $product->stock > 10 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $product->stock ?? 0 }}
                                    </span>
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button wire:click="changeStatus({{ $product->id }})" class="focus:outline-none">
                                        @if ($product->status == \App\Models\Product::PUBLICADO)
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 ring-1 ring-blue-400">
                                                PUBLICADO
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 ring-1 ring-gray-400">
                                                BORRADOR
                                            </span>
                                        @endif
                                    </button>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button wire:click="$emit('deleteProduct', {{ $product->id }})" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>

                                {{-- SUB-FILA DE VARIANTES --}}
                                <template x-if="open">
                                    <tr class="bg-gray-50/50">
                                        <td colspan="8" class="px-8 py-4 border-l-4 border-violet-500 shadow-inner">
                                            <div class="py-2">
                                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Variantes ({{ $product->variants->count() }})</h4>
                                                <table class="w-full text-left">
                                                    <thead>
                                                        <tr class="text-[10px] text-gray-400 uppercase">
                                                            <th class="pb-2">SKU</th>
                                                            <th class="pb-2">Atributos</th>
                                                            <th class="pb-2 text-center">Precio</th>
                                                            <th class="pb-2 text-center">Stock</th>
                                                            <th class="pb-2 text-center">Oferta Flash</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-sm">
                                                        @foreach($product->variants as $variant)
                                                            <tr class="border-t border-gray-200 bg-white/50">
                                                                <td class="py-2 text-gray-600 font-mono text-xs">{{ $variant->sku }}</td>
                                                                <td class="py-2">
                                                                    <div class="flex flex-wrap gap-1">
                                                                        @foreach($variant->attributeOptions as $option)
                                                                            <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] font-bold text-gray-700">
                                                                                {{ $option->attribute->name }}: {{ $option->value }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                                <td class="py-2 text-center font-bold text-gray-800">S/ {{ number_format($variant->price, 2) }}</td>
                                                                <td class="py-2 text-center">
                                                                    <span class="font-black {{ $variant->stock > 5 ? 'text-gray-700' : 'text-red-600' }}">{{ $variant->stock }}</span>
                                                                </td>
                                                                <td class="py-2 text-center">
                                                                    @if($variant->flashOffer?->status)
                                                                        <span class="text-orange-500 font-bold text-xs">S/ {{ number_format($variant->flashOffer->flash_price, 2) }}</span>
                                                                    @else
                                                                        <span class="text-gray-300">---</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 font-medium">No se encontraron productos que coincidan con tu búsqueda.</p>
                </div>
            @endif

            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $products->links() }}
            </div>
        </x-table-fixed-header>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('deleteProduct', productId => {
                Swal.fire({
                    title: '¿Eliminar producto?',
                    text: "Esto eliminará también todas sus variantes y stock. Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('delete', productId);
                        Swal.fire(
                            '¡Eliminado!',
                            'El producto ha sido eliminado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</div>
