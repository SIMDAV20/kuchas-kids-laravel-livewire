<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
    <div>
        <h1 class="text-3xl text-center font-semibold mb-8">
            Complete esta información para crear un producto
        </h1>

        <div class="bg-white shadow-xl rounded-lg p-6 mb-4">

            <div class="grid grid-cols-2 gap-6 mb-3">
                {{-- Categoría --}}
                <div>
                    <x-label value="Categorías" />
                    {{-- para escuchar los cambios al seleccionar se una un wire:model --}}
                    <select class="w-full form-control" wire:model="category_id">
                        <option value="" selected disabled>Seleccione una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <x-input-error for="category_id" />
                </div>

                {{-- Subcategoria --}}
                <div>
                    <x-label value="Subcategorías" />
                    <select class="w-full form-control" wire:model="subcategory_id">
                        <option value="" selected disabled>Seleccione una subcategoría</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                        @endforeach
                    </select>

                    <x-input-error for="subcategory_id" />
                </div>
            </div>

            <div class="mb-4">
                <x-label value="Nombre" />
                <x-input type="text" wire:model="name" class="w-full" placeholder="Ingrese el nombre del producto" />
                <x-input-error for="name" />
            </div>

            <div class="mb-4">
                <x-label value="Slug" />
                <x-input type="text" wire:model="slug" disabled class="w-full bg-gray-200"
                    placeholder="Ingrese el slug del producto" />
                <x-input-error for="slug" />
            </div>

            {{-- Descripcion --}}
            <div class="mb-4">
                {{-- ignore no renderiza el skeditor --}}
                <div wire:ignore>
                    <x-label value="Descripción" />
                    {{-- para inicializar alphine colocar x-data en el tag --}}
                    {{-- TODO: Cambiarlo por el summernote --}}
                    <textarea class="form-control w-full" wire:model="description" x-data x-init="ClassicEditor
                        .create($refs.miEditor)
                        .then(function(editor) {
                            editor.model.document.on('change:data', () => {
                                @this.set('description', editor.getData())
                            })
                        })
                        .catch(error => {
                            console.error(error);
                        });" x-ref="miEditor"></textarea>
                </div>
                <x-input-error for="description" />
            </div>

            {{-- Marca --}}
            @if (count($brands) > 0)
                <div class="mb-4 md:w-1/2">
                    <x-label value="Marca" />
                    <select class="form-control w-full" wire:model="brand_id">
                        <option value="" selected disabled>Seleccione una marca</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="brand_id" />
                </div>
            @endif
            <div class="grid grid-cols-4 gap-6 mb-4">
                {{-- Cantidad --}}
                {{-- @if ($options == '') --}}
                <div>
                    <x-label value="Cantidad" />
                    <x-input type="number" wire:model="quantity" class="w-full" />
                    <x-input-error for="quantity" />
                </div>
                {{-- @endif
                @if ($options == '' || $options == 'colors') --}}
                {{-- Precio --}}
                <div>
                    <x-label value="Precio" />
                    <x-input type="number" wire:model="price" class="w-full" step=".01" />
                    <x-input-error for="price" />
                </div>
                {{-- Precio Oferta --}}
                <div>
                    <x-label value="Precio Oferta" />
                    <x-input type="number" wire:model="offer_price" class="w-full" step=".01" />
                    <x-input-error for="offer_price" />
                </div>
                {{-- Fecha Oferta --}}
                {{-- <div>
                    <x-label value="Fecha Oferta" />
                    <x-input type="date" wire:model="offer_date" class="w-full" />
                    <x-input-error for="offer_date" />
                </div> --}}
                {{-- <x-date-picker wire:model="offer_date" /> --}}
                {{-- @endif --}}
            </div>
        </div>
    </div>

    <div class="flex mt-4">
        <x-button wire:loading.attr="disabled" wire:target="save" wire:click="save" class="ml-auto">
            Crear Producto
        </x-button>
    </div>
</div>
