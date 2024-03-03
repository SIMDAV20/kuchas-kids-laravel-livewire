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
        {{-- START SECTION TO ADD IMAGES --}}
        <section class="grid md:grid-cols-6">
          <div class="mb-2 flex flex-wrap flex-col justify-center items-center col-span-2">

            <div class="text-center mt-3">
              <x-file-attachment wire:model="photo" :file="$photo" mode="profile"
                profile-class="w-48 h-48 rounded-lg" accept="image/jpg,image/jpeg,image/png" />

              <p class="text-gray-400 my-2 text-sm">Tamaño: 450px * 450px</p>
              @error($photo)
                <p class="text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <x-button wire:click="uploadImage" class="ml-2">Agregar Imagen</x-button>
          </div>

          {{-- GALLERY IMAGES --}}
          <div x-data="{ selectedImages: @entangle('selectedImages') }" class="p-2 my-4 overflow-y-auto overflow-x-hidden col-span-4">

            <div class="flex flex-wrap  mb-3">
              {{-- SEARCHING IMAGES --}}
              <div class="grow">
                {{-- <x-label value="Buscar" /> --}}
                <x-input type="search" placeholder="Busqueda..." wire:model="search" class="w-full" />
              </div>

              <p class="relative flex items-center grow-0 mx-2">
                Cantidad de productos seleccionados: <span class="font-semibold text-lg mx-2">
                  {{ $selectedImages ? count($selectedImages) : 0 }}
                </span>
              </p>
            </div>

            <div class="flex flex-wrap gap-2 justify-center">
              @foreach ($images as $key => $image)
                <label for="image-{{ $item->id }}-{{ $key }}">
                  <div
                    class="relative border h-44 w-44 bg-contain bg-center bg-no-repeat cursor-pointer {{ in_array($image->url, $selectedImages) ? 'border-2 border-orange-500' : 'border-gray-800' }}"
                    style="background-image: url({{ Storage::url($image->url) }})">

                    <i class="fas fa-trash text-red-500 absolute top-2 cursor-pointer right-2"
                      wire:click="$emit('deleteImageProduct', {{ $image->id }})"></i>

                    <input id="image-{{ $item->id }}-{{ $key }}" type="checkbox"
                      class="form-checkbox hidden" x-model="selectedImages" value="{{ $image->url }}">
                  </div>
                </label>
              @endforeach
            </div>

            <div class="mt-4">
              {{ $images->links('pagination-links') }}
            </div>
          </div>
        </section>

        {{-- END SECTION TO ADD IMAGES --}}
        <hr class="mb-4">
        <section>
          <h3 class="font-semibold text-gray-700 text-xl">Ordenar las imagenes actuales</h3>

          {{ $item->gallery }}

        </section>

        @push('scripts')
          <script>
            Livewire.on('deleteImageProduct', imageId => {
              Swal.fire({
                title: 'Esta seguro de eliminar el registro?',
                text: "Si la imagen esta en otros productos, tambien serán eliminados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
              }).then((result) => {
                if (result.isConfirmed) {

                  Livewire.emitTo('admin.global-gallery', 'delete', imageId);

                  Swal.fire(
                    'Eliminado!', 'El resgistro ha sido eliminado.', 'success'
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
