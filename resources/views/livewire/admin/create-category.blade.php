<div>
    <x-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Crear Nueva Categoría
        </x-slot>
        <x-slot name="description">
            Complete la información necesario para poder crear una nueva categoría
        </x-slot>
        {{-- el form tiene un grid de 6 columnas --}}
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Nombre
                </x-label>
                <x-input wire:model="createForm.name" type="text" class="w-full mt-1" />

                <x-input-error for="createForm.name" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Slug
                </x-label>
                <x-input wire:model="createForm.slug" type="text" class="w-full bg-gray-200 mt-1" disabled />

                <x-input-error for="createForm.slug" />
            </div>
            {{-- <div class="col-span-6 sm:col-span-4">
                <x-label class="flex justify-between items-center">
                    Ícono

                    <a class="text-blue-400" href="https://fontawesome.com/v5.15/icons?d=gallery&p=2&m=free"
                        target="_blank">FontAwesome</a>
                </x-label>
                <x-input wire:model.defer="createForm.icon" type="text" class="w-full mt-1" />

                <x-input-error for="createForm.icon" />
            </div> --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Marcas
                </x-label>
                <div class="grid grid-cols-4">
                    @foreach ($brands as $brand)
                        <x-label>
                            <x-checkbox wire:model.defer="createForm.brands" name="brands[]"
                                value="{{ $brand->id }}" />
                            {{ $brand->name }}
                        </x-label>
                    @endforeach
                </div>

                <x-input-error for="createForm.brands" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Imagen
                </x-label>
                <x-input wire:model="createForm.image" accept="image/*" type="file" class="w-full mt-1"
                    id="{{ $rand }}" />

                <x-input-error for="createForm.image" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                Categoría creada
            </x-action-message>
            <x-button>
                Agregar
            </x-button>
        </x-slot>
    </x-form-section>

    <x-action-section>
        <x-slot name="title">
            Lista de Categorías
        </x-slot>
        <x-slot name="description">
            Aqui encontrará todas las categorías agregadas
        </x-slot>
        <x-slot name="content">

            <table class="text-gray-600 mb-3">
                <thead class="border-b border-gray-300s">
                    <tr class="text-left">
                        <th class="py-2 w-full">Nombre</th>
                        <th class="py-2 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($categories as $category)
                        <tr wire:key="category-{{ $category->id }}">
                            <td class="py-2">
                                <span class="uppercase">
                                    {{ $category->name }}
                                </span>
                            </td>
                            <td class="py-2">
                                <div wire:ignore class="flex divide-x divide-gray-300 font-semibold">
                                    <a href="{{ route('admin.categories.show', $category) }}"
                                        class="pr-2 hover:text-purple-600 cursor-pointer">
                                        Ver
                                    </a>
                                    <a wire:click="edit('{{ $category->slug }}')"
                                        class="px-2 hover:text-blue-600 cursor-pointer">
                                        Editar
                                    </a>
                                    {{-- tiene q estar entre comillas el $category->slug sino no se va enviar como cadena --}}
                                    <a wire:click="$emit('deleteCategory', '{{ $category->slug }}')"
                                        class="pl-2 hover:text-red-600 cursor-pointer">
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="text-gray-600">
                <thead class="border-b border-gray-300s">
                    <tr class="text-left">
                        <th class="py-2 w-full">Nombre</th>
                        <th class="py-2">Orden</th>
                    </tr>
                </thead>
                <tbody wire:sortable="updateCategoriesPosition()" class="divide-y divide-gray-300">
                    @foreach ($categories as $category)
                        <tr wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}">
                            <td class="py-2">
                                <i class="fas fa-allergies cursor-pointer"></i>
                                <span class="uppercase">
                                    {{ $category->name }}
                                </span>
                            </td>
                            <td class="py-2 text-center">
                                {{  $category->position }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-action-message class="mr-3 mt-2 text-blue-600" on="updated_positions">
                Posiciones Actualizadas
            </x-action-message>
        </x-slot>
    </x-action-section>

    {{-- Modal --}}
    <x-dialog-modal wire:model="editForm.open">
        <x-slot name="title">
            Editar Categoría
        </x-slot>

        <x-slot name="content">
            <div class="space-y-3">

                <div>
                    @if ($editImage)
                        <img class="w-full h-64 object-cover object-center" src="{{ $editImage->temporaryUrl() }}"
                            alt="">
                    @else
                        <img class="w-full h-64 object-cover object-center"
                            src="{{ @Storage::url($editForm['image']) }}" alt="">
                    @endif
                </div>

                <div>
                    <x-label>
                        Nombre
                    </x-label>
                    <x-input wire:model="editForm.name" type="text" class="w-full mt-1" />

                    <x-input-error for="editForm.name" />
                </div>
                <div>
                    <x-label>
                        Slug
                    </x-label>
                    <x-input wire:model="editForm.slug" type="text" class="w-full bg-gray-200 mt-1" disabled />

                    <x-input-error for="editForm.slug" />
                </div>
                <div>
                    <x-label>
                        Marcas
                    </x-label>
                    <div class="grid grid-cols-4">
                        @foreach ($brands as $brand)
                            <x-label>
                                <x-checkbox wire:model.defer="editForm.brands" name="brands[]"
                                    value="{{ $brand->id }}" />
                                {{ $brand->name }}
                            </x-label>
                        @endforeach
                    </div>

                    <x-input-error for="editForm.brands" />
                </div>
                <div>
                    <x-label>
                        Imagen
                    </x-label>
                    <x-input wire:model="editImage" accept="image/*" type="file" class="w-full mt-1" />

                    <x-input-error for="editImage" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('editForm.open', false)">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="editImage, update">
                Actualizar
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
