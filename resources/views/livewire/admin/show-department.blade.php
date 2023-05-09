<div class="container py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
            Departamento: {{ $department->name }}
        </h2>
    </x-slot>

    <x-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Agregar nueva ciudad
        </x-slot>
        <x-slot name="description">
            En esta sección podrá agregar una nueva ciudad
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Nombre
                </x-label>
                <x-input wire:model.defer="createForm.name" type="text" class="w-full mt-1" />

                <x-input-error for="createForm.name" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Costo de envío
                </x-label>
                <x-input wire:model.defer="createForm.cost" min="1" max="200" type="number"
                    class="w-full mt-1" />

                <x-input-error for="createForm.cost" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                ciudad creada
            </x-action-message>
            <x-button>
                Agregar
            </x-button>
        </x-slot>
    </x-form-section>

    <x-action-section>
        <x-slot name="title">
            Lista de ciudades
        </x-slot>
        <x-slot name="description">
            Aqui encontrará todas las ciudades agregadas
        </x-slot>
        <x-slot name="content">
            <table class="text-gray-600">
                <thead class="border-b border-gray-300s">
                    <tr class="text-left">
                        <th class="py-2 w-full">Nombre</th>
                        <th class="py-2">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($provinces as $province)
                        <tr>
                            <td class="py-2">
                                <a href="{{ route('admin.provinces.show', $province) }}"
                                    class="uppercase underline hover:text-blue-600">
                                    {{ $province->name }}
                                </a>
                            </td>
                            <td class="py-2">
                                <div class="flex divide-x divide-gray-300 font-semibold">
                                    <a wire:click="edit('{{ $province->id }}')"
                                        class="pr-2 hover:text-blue-600 cursor-pointer">
                                        Editar
                                    </a>
                                    {{-- tiene q estar entre comillas el $province->id sino no se va enviar como cadena --}}
                                    <a wire:click="$emit('deleteCity', '{{ $province->id }}')"
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
            Editar ciudad
            {{ $province }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label>
                    Nombre
                </x-label>
                <x-input wire:model.defer="editForm.name" type="text" class="w-full" />

                <x-input-error for="editForm.name" />
            </div>

            <div>
                <x-label>
                    Costo de envío
                </x-label>
                <x-input wire:model.defer="editForm.cost" min="1" max="200" type="number" class="w-full" />

                <x-input-error for="editForm.cost" />
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
            Livewire.on('deleteCity', cityId => {
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

                        Livewire.emitTo('admin.show-department', 'delete', cityId);

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
