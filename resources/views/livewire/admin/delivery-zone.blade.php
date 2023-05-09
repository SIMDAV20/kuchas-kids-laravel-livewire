<div class="container py-12">
    <x-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Agregar nueva zona
        </x-slot>
        <x-slot name="description">
            En esta sección podrá agregar una nueva zona para el costo del delivery por un grupo específico de distritos
        </x-slot>
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
                    Costo de delivery
                </x-label>
                <x-input wire:model="createForm.cost" type="number" min="1" class="w-full mt-1" />

                <x-input-error for="createForm.cost" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-label>
                    Distritos
                </x-label>
                <div class="grid grid-cols-3 mt-2">
                    @forelse ($districts as $district)
                        <x-label>
                            <x-checkbox wire:model.defer="createForm.districts" name="districts[]"
                                value="{{ $district->id }}" />
                            {{ $district->name }}
                        </x-label>
                    @empty
                        <h3 class="mt-2 text-lg text-blue-400">Sin distritos libres de zonas</h3>
                    @endforelse
                </div>
                <x-input-error for="createForm.districts" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                zona creada
            </x-action-message>
            @if ($districts->count() > 0)
                <x-button>
                    Agregar
                </x-button>
            @endif
        </x-slot>
    </x-form-section>

    <x-action-section>
        <x-slot name="title">
            Lista de Zonas
        </x-slot>
        <x-slot name="description">
            Aqui encontrará todas las zonas agregadas
        </x-slot>
        <x-slot name="content">
            <table class="text-gray-600 w-full">
                <thead class="border-b border-gray-300s">
                    <tr class="text-left">
                        <th class="py-2 w-2/5">Nombre</th>
                        <th class="py-2 w-2/5">Costo</th>
                        <th class="py-2">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($zones as $zone)
                        <tr>
                            <td class="py-2">
                                <span class="uppercase">
                                    {{ $zone->name }}
                                </span>
                            </td>
                            <td class="py-2">
                                <span class="uppercase">
                                    S/ {{ number_format($zone->cost, 2) }}
                                </span>
                            </td>
                            <td class="py-2">
                                <div class="flex divide-x divide-gray-300 font-semibold">
                                    <a wire:click="edit('{{ $zone->id }}')"
                                        class="pr-2 hover:text-blue-600 cursor-pointer">
                                        Editar
                                    </a>
                                    <a wire:click="$emit('deleteZone', '{{ $zone->id }}')"
                                        class="pl-2 hover:text-red-600 cursor-pointer">
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-action-section>

    {{-- Modal --}}
    <x-dialog-modal wire:model="editForm.open">
        <x-slot name="title">
            Editar zona
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label>
                    Nombre
                </x-label>
                <x-input wire:model="editForm.name" type="text" class="w-full" />

                <x-input-error for="editForm.name" />
            </div>
            <div class="mb-4">
                <x-label>
                    Costo de delivery
                </x-label>
                <x-input wire:model="editForm.cost" type="number" min="1" class="w-full mt-1" />

                <x-input-error for="editForm.cost" />
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Distritos de la zona
                </x-label>
                <div class="grid grid-cols-3">
                    @forelse ($editDistricts as $key => $district)
                        <x-label>
                            <x-checkbox wire:model.defer="editForm.districts" name="districts[]"
                                value="{{ $district->id }}" />
                            {{ $district->name }}
                        </x-label>
                    @empty
                        <h3 class="mt-2 text-lg text-blue-400">Sin distritos libres de zonas</h3>
                    @endforelse
                </div>
                <x-input-error for="editForm.districts" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('editForm.open', false)">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>


    @push('scripts')
        <script>
            Livewire.on('deleteZone', ZoneId => {
                Swal.fire({
                    title: 'Esta seguro de eliminar el registro?',
                    text: "Acción irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.delivery-zone', 'delete', ZoneId);

                        Swal.fire(
                            'Eliminado!',
                            'El resgistro ha sido eliminado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</div>
