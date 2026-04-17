<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">

    {{-- BARRA DE TÍTULO SUPERIOR --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Nuevo Producto</h1>
            <p class="text-sm text-gray-500 mt-1">Complete la información base. Podrá agregar variantes, imágenes y precios detallados después.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
            <x-button wire:loading.attr="disabled" wire:target="save" wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all px-8">
                Crear Producto
            </x-button>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        {{-- COLUMNA PRINCIPAL --}}
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
                            <x-input-error for="category_id" />
                        </div>
                        <div>
                            <x-label value="Subcategoría" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                            <select class="w-full form-control rounded-xl border-gray-200 focus:ring-indigo-500" wire:model="subcategory_id">
                                <option value="" disabled>Seleccione...</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="subcategory_id" />
                        </div>
                    </div>

                    <div>
                        <x-label value="Nombre del Producto" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                        <x-input type="text" wire:model="name" class="w-full rounded-xl" placeholder="Ej. Polera de Algodón" />
                        <x-input-error for="name" />
                    </div>

                    <div>
                        <x-label value="Slug" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                        <x-input type="text" wire:model="slug" disabled class="w-full rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed" />
                        <x-input-error for="slug" />
                    </div>

                    @if (count($brands) > 0)
                        <div>
                            <x-label value="Marca" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                            <select class="w-full form-control rounded-xl border-gray-200" wire:model="brand_id">
                                <option value="">Sin marca</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="brand_id" />
                        </div>
                    @endif

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
                        <textarea class="form-control w-full" rows="4" wire:model="description"
                            x-data x-init="ClassicEditor
                                .create($refs.miEditor)
                                .then(function(editor) {
                                    editor.model.document.on('change:data', () => {
                                        @this.set('description', editor.getData())
                                    })
                                })
                                .catch(error => console.error(error));" x-ref="miEditor"></textarea>
                        <style>
                            .ck-editor__editable_inline { min-height: 250px; margin-bottom: 10px; }
                        </style>
                    </div>
                    <x-input-error for="description" />
                </div>
            </div>

        </div>

        {{-- COLUMNA LATERAL --}}
        <div class="space-y-6">

            {{-- PRECIO BASE --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center text-sm">
                        <i class="fas fa-tag mr-2 text-green-500"></i> Precio Base
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <x-label value="Precio" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                        <x-input type="number" wire:model="price" class="w-full rounded-xl" step=".01" placeholder="0.00" />
                        <x-input-error for="price" />
                    </div>
                    <div>
                        <x-label value="Precio Oferta" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                        <x-input type="number" wire:model="offer_price" class="w-full rounded-xl" step=".01" placeholder="0.00" />
                        <x-input-error for="offer_price" />
                    </div>
                    <div>
                        <x-label value="Cantidad en Stock" class="text-xs font-bold uppercase text-gray-400 mb-1" />
                        <x-input type="number" wire:model="quantity" class="w-full rounded-xl" placeholder="0" />
                        <x-input-error for="quantity" />
                    </div>
                </div>
            </div>

            {{-- TIP VARIANTES --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 space-y-2">
                <p class="text-xs font-black uppercase tracking-widest text-indigo-400 flex items-center gap-2">
                    <i class="fas fa-lightbulb"></i> Variantes
                </p>
                <p class="text-sm text-indigo-700">
                    Si el producto tiene <strong>tallas, colores u otras opciones</strong>, podrás agregar variantes desde la página de edición.
                </p>
                <p class="text-xs text-indigo-500">
                    Cada variante tiene su propio precio, precio oferta, stock e imágenes. Los precios base se ignoran cuando hay variantes activas.
                </p>
            </div>

        </div>
    </div>
</div>
