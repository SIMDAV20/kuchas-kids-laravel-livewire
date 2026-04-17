<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
    {{-- BARRA DE TÍTULO SUPERIOR --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">
                {{ $product->name }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">ID Producto: <span class="font-mono text-indigo-600">#{{ $product->id }}</span> | Última actualización: {{ $product->updated_at->diffForHumans() }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
            <x-button wire:loading.attr="disabled" wire:target="save" wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all px-8">
                Guardar Cambios
            </x-button>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- COLUMNA PRINCIPAL (IZQUIERDA) --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- INFORMACIÓN BÁSICA --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold flex items-center text-gray-800">
                        <i class="fas fa-info-circle mr-2 text-indigo-500"></i> Información del Producto
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label value="Categoría" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                            <select class="w-full form-control rounded-xl border-gray-200 focus:ring-indigo-500" wire:model="category_id">
                                <option value="" disabled>Seleccione...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-label value="Subcategoría" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                            <select class="w-full form-control rounded-xl border-gray-200 focus:ring-indigo-500" wire:model="product.subcategory_id">
                                <option value="" disabled>Seleccione...</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-label value="Nombre del Producto" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                        <x-input type="text" wire:model="product.name" class="w-full rounded-xl" placeholder="Ej. Polera de Algodón" />
                        <x-input-error for="product.name" />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label value="Marca" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                            <select class="w-full form-control rounded-xl border-gray-200" wire:model="product.brand_id">
                                <option value="">Sin marca</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-label value="Enlace de Video (YouTube)" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                            <x-input type="text" wire:model="product.video" class="w-full rounded-xl" placeholder="https://..." />
                        </div>
                    </div>
                </div>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i> Descripción
                    </h3>
                </div>
                <div class="p-6">
                    <div wire:ignore>
                        <textarea class="form-control w-full" rows="4" wire:model="product.description" x-init="ClassicEditor
                            .create($refs.miEditor)
                            .then(function(editor) {
                                editor.model.document.on('change:data', () => {
                                    @this.set('product.description', editor.getData())
                                })
                            })
                            .catch(error => console.error(error));" x-data x-ref="miEditor"></textarea>
                        <style>
                            .ck-editor__editable_inline { min-height: 250px; margin-bottom: 10px; }
                            .ck-editor { border-radius: 12px !important; }
                        </style>
                    </div>
                </div>
            </div>

            {{-- VARIANTES --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-indigo-50/30 flex justify-between items-center">
                    <h3 class="font-bold text-indigo-900 flex items-center italic">
                        <i class="fas fa-layer-group mr-2 text-indigo-500"></i> Gestión de Variantes
                    </h3>
                    <x-button wire:click="generateVariants" class="bg-indigo-600 text-[10px]">
                        Re-Generar Matriz
                    </x-button>
                </div>
                <div class="p-6">
                    {{-- Selector de Atributos --}}
                    <div class="grid md:grid-cols-2 gap-6 mb-8 border-b border-dashed pb-8">
                        @foreach ($allAttributes as $attribute)
                            <div wire:key="attr-group-{{ $attribute->id }}">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">{{ $attribute->name }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($attribute->options as $option)
                                        <label wire:key="option-{{ $option->id }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg border cursor-pointer transition-all {{ in_array($option->id, $selectedAttributes[$attribute->id] ?? []) ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500' : 'border-gray-200 bg-white hover:bg-gray-50' }}">
                                            <input type="checkbox" value="{{ $option->id }}" wire:model="selectedAttributes.{{ $attribute->id }}" class="hidden">
                                            @if ($option->hex)
                                                <span class="w-3 h-3 rounded-full mr-2 border border-black/10" style="background-color: {{ $option->hex }}"></span>
                                            @endif
                                            <span class="text-xs font-medium">{{ $option->value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tabla de Variantes --}}
                    @if ($product->variants->count() > 0)
                        <div class="mb-4">
                            @if (count($selectedVariants))
                                <div class="flex items-center gap-3 p-3 bg-violet-50 rounded-xl border border-violet-200 mb-4 animate-fade-in shadow-sm">
                                    <span class="text-xs font-bold text-violet-700 ml-2">{{ count($selectedVariants) }} seleccionadas:</span>
                                    <button wire:click="activateSelectedVariants" class="text-[10px] font-black uppercase bg-white border border-green-200 text-green-600 px-3 py-1 rounded-lg hover:bg-green-50 transition-colors">Activar</button>
                                    <button wire:click="deactivateSelectedVariants" class="text-[10px] font-black uppercase bg-white border border-gray-200 text-gray-400 px-3 py-1 rounded-lg hover:bg-gray-50 transition-colors">Desactivar</button>
                                    <button wire:click="deleteSelectedVariants" onclick="confirm('¿Eliminar seleccionadas?') || event.stopImmediatePropagation()" class="text-[10px] font-black uppercase bg-white border border-red-200 text-red-500 px-3 py-1 rounded-lg hover:bg-red-50 transition-colors">Eliminar</button>
                                </div>
                            @endif
                            
                            <x-table-fixed-header maxHeight="500px">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50 sticky top-0 z-20 shadow-sm border-b border-gray-100">
                                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                            <th class="px-4 py-3 text-left w-10">
                                                <input type="checkbox" wire:model="selectAllVariants" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            </th>
                                            <th class="px-4 py-3 text-left">Imágenes</th>
                                            <th class="px-4 py-3 text-left">Variante</th>
                                            <th class="px-4 py-3 text-left">SKU</th>
                                            <th class="px-4 py-3 text-center">Precio</th>
                                            <th class="px-4 py-3 text-center">Stock</th>
                                            <th class="px-4 py-3 text-center w-10">Estado</th>
                                            <th class="px-4 py-3 text-center w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach ($product->variants as $variant)
                                            <tr wire:key="variant-row-{{ $variant->id }}" class="hover:bg-gray-50/50 transition-colors {{ in_array($variant->id, $selectedVariants) ? 'bg-indigo-50/30' : '' }}">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" value="{{ $variant->id }}" wire:model="selectedVariants" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex -space-x-2 overflow-hidden mb-1">
                                                        @php
                                                            $assignedImages = $product->images_relations->whereIn('id', $variant->images ?? []);
                                                        @endphp
                                                        @foreach($assignedImages as $img)
                                                            <img src="{{ Storage::url($img->url) }}" class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover">
                                                        @endforeach
                                                    </div>
                                                    <button wire:click="openImageModal({{ $variant->id }})" class="text-[10px] font-bold text-indigo-500 uppercase">
                                                        <i class="fas fa-camera"></i> Asignar
                                                    </button>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="text-xs font-black text-gray-800 tracking-tight">
                                                        {{ $variant->attributeOptions->pluck('value')->implode(' + ') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="text" value="{{ $variant->sku }}" 
                                                        wire:change="updateVariant({{ $variant->id }}, 'sku', $event.target.value)"
                                                        class="text-[10px] font-mono border-gray-200 rounded-md w-full bg-gray-50 focus:bg-white transition-all">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="number" step="0.01" value="{{ $variant->price }}" 
                                                        wire:change="updateVariant({{ $variant->id }}, 'price', $event.target.value)"
                                                        class="text-xs font-bold border-gray-200 rounded-md w-20 text-center">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="number" value="{{ $variant->stock }}" 
                                                        wire:change="updateVariant({{ $variant->id }}, 'stock', $event.target.value)"
                                                        class="text-xs font-bold border-gray-200 rounded-md w-16 text-center {{ $variant->stock < 5 ? 'text-red-500' : 'text-gray-700' }}">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @livewire('admin.change-status-product', ['item_id' => $variant->id, 'model' => 'ProductVariant'], key('status-variant-' . $variant->id))
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button wire:click="deleteVariant({{ $variant->id }})" class="text-gray-300 hover:text-red-500 transition-colors">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-table-fixed-header>
                        </div>
                    @else
                        <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <p class="text-sm text-gray-400 italic">No hay variantes para este producto. Selecciona opciones y genera la matriz.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- GALERÍA --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center uppercase text-sm tracking-tight">
                        <i class="fas fa-images mr-2 text-indigo-500"></i> Fotos del Producto
                    </h3>
                </div>
                <div class="p-6">
                    @livewire('admin.gallery-images-products', ['item_id' => $product->id, 'model' => 'Product'], key('gallery-' . $product->id))
                </div>
            </div>
        </div>

        {{-- SIDEBAR STICKY (DERECHA) --}}
        <div class="space-y-8">
            <div class="sticky top-8 space-y-8">
                
                {{-- CARD DE IMAGEN DESTACADA --}}
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="p-1">
                        @if($product->images_relations->count() > 0)
                            <img src="{{ Storage::url($product->images_relations->first()->url) }}" class="w-full h-64 object-cover rounded-xl" alt="Preview">
                        @else
                            <div class="w-full h-64 bg-gray-100 flex items-center justify-center rounded-xl">
                                <i class="fas fa-image text-gray-300 text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 text-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Vista Previa Principal</span>
                    </div>
                </div>

                {{-- CARD DE ESTADO --}}
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <h4 class="text-xs font-bold text-gray-800 uppercase mb-4 tracking-tighter">Estado y Visibilidad</h4>
                        @livewire('admin.status-product', ['product' => $product], key('sidebar-status-' . $product->id))
                        
                        <hr class="my-6 border-gray-100">

                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Stock Total:</span>
                                <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $product->stock }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Precio Min:</span>
                                <span class="font-black text-gray-800">S/ {{ number_format($product->getMinPrice(), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD DE OFERTA FLASH (SI APLICA) --}}
                @if($product->flashOffer)
                    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center text-orange-700 font-black text-xs uppercase mb-2">
                            <i class="fas fa-bolt mr-2 text-orange-500"></i> Oferta Flash Activa
                        </div>
                        <p class="text-2xl font-black text-orange-800 italic">S/ {{ number_format($product->flashOffer->flash_price, 2) }}</p>
                        <p class="text-[10px] text-orange-600 mt-1 uppercase">Finaliza {{ $product->flashOffer->end_at->diffForHumans() }}</p>
                    </div>
                @endif

                {{-- ACCIONES DE PELIGRO --}}
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-red-100 ring-1 ring-red-50">
                    <div class="p-6">
                        <x-danger-button wire:click="$emit('deleteProduct')" class="w-full justify-center !rounded-xl py-3 shadow-red-200 shadow-lg">
                            <i class="fas fa-trash-alt mr-2"></i> Eliminar Definitivamente
                        </x-danger-button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL IMÁGENES (MISMO QUE ANTES) --}}
    {{-- <x-dialog-modal wire:model="isImageModalOpen">
        <x-slot name="title">Asignar Fotos a Variante</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $image)
                    <div wire:click="toggleImageSelection({{ $image->id }})" 
                        class="relative cursor-pointer border-2 rounded-xl overflow-hidden {{ in_array($image->id, $selectedImageIds) ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-transparent' }}">
                        <img src="{{ Storage::url($image->url) }}" class="w-full h-24 object-cover">
                    </div>
                @endforeach
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isImageModalOpen', false)">Cerrar</x-secondary-button>
            <x-button class="ml-2 bg-indigo-600" wire:click="saveVariantImages">Guardar</x-button>
        </x-slot>
    </x-dialog-modal> --}}

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('saved', () => {
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                Toast.fire({ icon: 'success', title: 'Producto actualizado con éxito' });
            });
            Livewire.on('deleteProduct', () => {
                Swal.fire({
                    title: '¿Estás seguro?', text: "Se eliminarán también variantes e imágenes.", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Sí, eliminar!'
                }).then((result) => { if (result.isConfirmed) Livewire.emit('delete'); });
            });
        </script>
    @endpush
</div>
