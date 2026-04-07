<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        {{-- Crear Atributo --}}
        <div class="md:col-span-1">
            <x-jet-section-title>
                <x-slot name="title">Atributos del Producto</x-slot>
                <x-slot name="description">Crea características dinámicas como Color, Talla, Tela, etc.</x-slot>
            </x-jet-section-title>

            <div class="bg-white p-6 rounded shadow mt-4">
                <div class="mb-4">
                    <x-jet-label for="name" value="Nombre del Atributo" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ej: Color, Talla" />
                    <x-jet-input-error for="name" class="mt-2" />
                </div>
                <div class="flex items-center justify-end">
                    <x-jet-button wire:click="saveAttribute">
                        Crear Atributo
                    </x-jet-button>
                </div>
            </div>
        </div>

        {{-- Listado de Atributos --}}
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-md p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atributo</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opciones</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attributes as $attribute)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $attribute->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($attribute->options as $option)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        @if($option->hex)
                                            <span class="w-3 h-3 rounded-full mr-1" style="background-color: {{ $option->hex }};"></span>
                                        @endif
                                        {{ $option->value }}
                                        <button wire:click="deleteOption({{ $option->id }})" class="ml-1 text-indigo-400 hover:text-indigo-600 focus:outline-none">
                                            &times;
                                        </button>
                                    </span>
                                    @endforeach
                                </div>
                                {{-- Añadir Opción --}}
                                <div class="mt-2 flex items-center gap-2">
                                    <input type="text" placeholder="Nueva opción" 
                                           wire:model.defer="optionValue" 
                                           wire:click="editOptions({{ $attribute->id }})"
                                           class="mt-1 block w-32 border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md h-8">
                                    @if($attribute->name == 'Color')
                                    <input type="color" wire:model.defer="optionHex" title="Elegir Color" class="h-8 w-8">
                                    @endif
                                    <button wire:click="saveOption" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase transition">
                                        + Agregar
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button onclick="confirm('¿Estás seguro de eliminar este atributo?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteAttribute({{ $attribute->id }})" 
                                        class="text-red-600 hover:text-red-900">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
