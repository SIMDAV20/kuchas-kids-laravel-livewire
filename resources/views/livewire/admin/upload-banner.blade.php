<div x-data class="container py-8" style="min-height: 600px">
  <div class="mb-2 flex justify-center items-center">

    <div class="text-center">
      <x-file-attachment wire:model="image" :file="$image" mode="profile" profile-class="w-48 h-48 rounded-lg"
        accept="image/jpg,image/jpeg,image/png" />

      <p class="text-gray-400 my-2 text-sm">Tamaño: 731px * 316px</p>

      @error($photo)
        <p class="text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex flex-col text-center">
      <x-button wire:click="uploadBanner" class="ml-2">Agregar Banner</x-button>
      <x-action-message class="mr-3 mt-2 text-blue-600" on="upload_banner">
        Imagen Agregada
      </x-action-message>
    </div>
  </div>
  <div class="bg-white shadow-lg rounded-lg p-6 mb-4">
    <h2 class="text-lg">Lista de Banners</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
      @foreach ($banners as $banner)
        <div class="relative p-2">
          <img src="{{ Storage::url($banner->photo) }}" alt="{{ $banner->photo }}">

          <i class="fas fa-trash text-red-500 absolute top-1 cursor-pointer"
            wire:click="$emit('deleteBanner', {{ $banner->id }})"></i>
        </div>
      @endforeach
    </div>
  </div>

  @push('scripts')
    <script>
      Livewire.on('deleteBanner', bannerId => {
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

            Livewire.emitTo('admin.upload-banner', 'delete', bannerId);

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
