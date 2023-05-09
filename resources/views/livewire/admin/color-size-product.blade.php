<div>
    <div class="my-12 bg-white shadow-xl rounded-lg p-6">
        {{-- Color --}}
        <div class="mb-6">
            <x-label value="Color" class="text-lg mb-2" />

            <div class="grid grid-cols-5 gap-4">
                @foreach ($colors as $color)
                    <label class="flex flex-nowrap items-center">
                        <input type="radio" name="color_id" wire:model="createForm.color_id"
                            value="{{ $color->id }}" />
                        <span class="ml-2 texrt-gray-700 capitalize">
                            {{ __($color->name) }}
                        </span>
                        <div class="w-6 h-6 rounded-full ml-2" style="background-color: {{ $color->hex }}"></div>
                    </label>
                @endforeach
            </div>

            <x-input-error for="color_id" />
        </div>

        {{-- Talla --}}
        <div class="mb-6">
            <x-label value="Talla" class="text-lg mb-2" />

            <div class="grid grid-cols-5 gap-4">
                @foreach ($sizes as $size)
                    <label class="flex flex-nowrap items-center">
                        <input type="radio" name="size_id" wire:model="createForm.size_id"
                            value="{{ $size->id }}" />
                        <span class="ml-2 texrt-gray-700 capitalize">
                            {{ $size->name }}
                        </span>
                    </label>
                @endforeach
            </div>

            <x-input-error for="size_id" />
        </div>


        <div class="grid md:grid-cols-3 gap-6 mb-4">
            {{-- Cantidad --}}
            <div>
                <x-label value="Cantidad" />
                <x-input type="number" wire:model="createForm.quantity" placeholder="Ingrese una cantidad"
                    class="w-full" />
                <x-input-error for="quantity" />
            </div>
            {{-- Precio --}}
            <div>
                <x-label value="Precio" />
                <x-input type="number" wire:model="createForm.price" class="w-full" />
                <x-input-error for="price" />
            </div>
            {{-- Precio Oferta --}}
            <div>
                <x-label value="Precio Oferta" />
                <x-input type="number" wire:model="createForm.offer_price" class="w-full" />
                <x-input-error for="offer_price" />
            </div>

        </div>

        <div class="flex justify-end items-center mt-4">

            <x-action-message class="mr-3" on="saved">
                Agregado
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="save" wire:click="save">
                Agregar
            </x-button>
        </div>
    </div>
    @if ($color_product_size->count())
        <div class="my-12 bg-white shadow-xl rounded-lg p-6">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2 w-1/3">
                            Color
                        </th>
                        <th class="px-4 py-2 w-1/3">
                            Talla
                        </th>
                        <th class="px-4 py-2 w-1/3">
                            Cantidad
                        </th>
                        <th class="px-4 py-2w-1/3">
                            Precio / Oferta
                        </th>
                        <th class="px-4 py-2 w-1/3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($color_product_sizes as $cps)
                        <tr wire:key="product_color-{{ $product_color->pivot->id }}" class="text-center">
                            <td class="capitalize px-4 py-2">
                                {{ __($colors->find($product_color->pivot->color_id)->name) }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $product_color->pivot->quantity }} unidades
                            </td>
                            <td class="px-4 py-2 flex">
                                <x-secondary-button wire:click="edit({{ $product_color->pivot->id }})"
                                    class="ml-auto mr-2" wire:loading.attr="disabled"
                                    wire:target="edit({{ $product_color->pivot->id }})">
                                    Actualizar
                                </x-secondary-button>
                                <x-danger-button
                                    wire:click="$emit('deleteColorProduct', {{ $product_color->pivot->id }})">
                                    Eliminar
                                </x-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Editar Colores
        </x-slot>

        <x-slot name="content">
            {{-- Color --}}
            <div class="mb-4">
                <x-label>
                    Color
                </x-label>
                <select class="form-control w-full" wire:model="pivot_color_id">
                    <option value="">Seleccione un color</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ ucfirst(__($color->name)) }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Cantidad --}}
            <div class="mb-4">
                <x-label>
                    Cantidad
                </x-label>
                <x-input wire:model="pivot_quantity" class="w-full" type="number" placeholder="Ingrese una cantidad" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
