<div class="container py-12">
    <x-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Agregar nueva marca
        </x-slot>
        <x-slot name="description">
            En esta sección podrá agregar una nueva marca
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
                    Slug
                </x-label>
                <x-input wire:model="createForm.slug" type="text" class="w-full bg-gray-200 mt-1" disabled />

                <x-input-error for="createForm.slug" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                marca creada
            </x-action-message>
            <x-button>
                Agregar
            </x-button>
        </x-slot>
    </x-form-section>

    <x-action-section>
        <x-slot name="title">
            Lista de Marcas
        </x-slot>
        <x-slot name="description">
            Aqui encontrará todas las marcas agregadas
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
                    @foreach ($brands as $brand)
                        <tr>
                            <td class="py-2">
                                <span class="uppercase">
                                    {{ $brand->name }}
                                </span>
                            </td>
                            <td class="py-2">
                                <div class="flex divide-x divide-gray-300 font-semibold">
                                    <a wire:click="edit('{{ $brand->id }}')"
                                        class="pr-2 hover:text-blue-600 cursor-pointer">
                                        Editar
                                    </a>
                                    {{-- tiene q estar entre comillas el $brand->id sino no se va enviar como cadena --}}
                                    <a wire:click="$emit('deleteBrand', '{{ $brand->id }}')"
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
            Editar marca
        </x-slot>

        <x-slot name="content">
            <div>
                <x-label>
                    Nombre
                </x-label>
                <x-input wire:model="editForm.name" type="text" class="w-full" />

                <x-input-error for="editForm.name" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Slug
                </x-label>
                <x-input wire:model="editForm.slug" type="text" class="w-full bg-gray-200 mt-1" disabled />

                <x-input-error for="editForm.slug" />
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
            Livewire.on('deleteBrand', brandId => {
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

                        Livewire.emitTo('admin.brand-component', 'delete', brandId);

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
