<div>
    <x-button color="green" class="ml-auto mr-2" wire:loading.attr="disabled" wire:target="edit()" wire:click="edit()">
        Galeria
    </x-button>


    <x-dialog-modal wire:model="open_gallery">
        <x-slot name="title">
            Galeria de Imagenes
        </x-slot>

        <x-slot name="content">
            <div class="border-2 border-blue-600">
                <div x-data class="container p-2" style="min-height: 600px">
                    <div class="mb-2 flex justify-center items-center">

                        <div class="text-center">
                            <x-file-attachment wire:model="photo" :file="$photo" mode="profile"
                                profile-class="w-48 h-48 rounded-lg" accept="image/jpg,image/jpeg,image/png" />

                            <p class="text-gray-400 my-2 text-sm">Tamaño: 450px * 450px</p>

                            @error($photo)
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-button wire:click="uploadImage" class="ml-2">Agregar Imagen</x-button>
                    </div>
                    <div class="p-2 mb-4 max-h-96">
                        {{-- <h2 class="text-lg">Galeria de Imagenes</h2> --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-3">
                            @foreach ($images as $image)
                                <div class="relative p-2">
                                    <img src="{{ Storage::url($image->url) }}" alt="{{ $image->url }}">

                                    <i class="fas fa-trash text-red-500 absolute top-1 cursor-pointer right-0"
                                        wire:click="$emit('deleteImageProduct', {{ $image->id }})"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @push('scripts')
                        <script>
                            Livewire.on('deleteImageProduct', imageId => {
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

                                        Livewire.emitTo('admin.gallery-images-products', 'delete', imageId);

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

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open_gallery', false)">
                Cerrar
            </x-secondary-button>
            {{-- <x-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-button> --}}
        </x-slot>
    </x-dialog-modal>
</div>
