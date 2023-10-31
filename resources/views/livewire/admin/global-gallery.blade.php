<div>
  <x-button color="green" class="ml-auto mr-2" wire:loading.attr="disabled" wire:target="edit()" wire:click="edit()">
    Galeria
  </x-button>


  <x-dialog-modal wire:model="open_gallery" :maxWidth="'max-w'">
    <x-slot name="title">
      Galeria de Imagenes
    </x-slot>

    <x-slot name="content">
      <div class="border-2 border-blue-600 px-2">
        <div class="mb-2 flex flex-wrap justify-center items-center">

          <div class="text-center mt-3">
            <x-file-attachment wire:model="photo" :file="$photo" mode="profile" profile-class="w-48 h-48 rounded-lg"
              accept="image/jpg,image/jpeg,image/png" />

            <p class="text-gray-400 my-2 text-sm">Tamaño: 450px * 450px</p>
            @error($photo)
              <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <x-button wire:click="uploadImage" class="ml-2">Agregar Imagen</x-button>
        </div>
        {{-- GALLERY IMAGES --}}
        <div class="p-2 my-4 overflow-y-auto overflow-x-hidden">
          {{-- <h2 class="text-lg">Galeria de Imagenes</h2> --}}

          {{ var_export($selectedImages) }}
          <div class="flex flex-wrap gap-2" x-data="{ selectedImages: @entangle('selectedImages') }">
            @foreach ($images as $key => $image)
              <label for="{{ $image->id }}">
                <div
                  class="relative border border-violet-150 h-44 w-44 bg-contain bg-center bg-no-repeat cursor-pointer"
                  style="background-image: url({{ Storage::url($image->url) }})">

                  <i class="fas fa-trash text-red-500 absolute top-2 cursor-pointer right-2"
                    wire:click="$emit('deleteImageProduct', {{ $image->id }})"></i>

                  <input id="{{ $image->id }}" type="checkbox" class="form-checkbox" x-model="selectedImages"
                    value="{{ $image->url }}">

                  {{-- <span class="absolute bottom-0 left-0 bg-black text-white p-1 rounded-tr-lg">{{ $key + 1 }}</span> --}}
                </div>
              </label>
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
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('open_gallery', false)">
        Cerrar
      </x-secondary-button>
      <x-button wire:click="update" class="ml-2" wire:loading.attr="disabled" wire:target="update">
        Actualizar
      </x-button>
    </x-slot>
  </x-dialog-modal>
</div>
