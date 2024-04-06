<x-action-section class="mb-6">
  <x-slot name="title">
    Logo principal de la web
  </x-slot>
  <x-slot name="description">
    Aqui podrá actualizar el logo
  </x-slot>
  <x-slot name="content">

    <div class="grid md:grid-cols-2">
      <div class="mb-2 flex justify-center items-center">

        <div class="text-center">
          <x-file-attachment wire:model="image" :file="$image" mode="profile" profile-class="w-48 h-48 rounded-lg"
            accept="image/jpg,image/jpeg,image/png" />

          <p class="text-gray-400 my-2 text-sm">Tamaño: 250px * 175px</p>

          @error($photo)
            <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
          @enderror
          <x-button type="button" wire:click="updateLogo" wire.loading.attr="disabled" wire.target="updateLogo"
            class="ml-2">Actualizar
            Logo</x-button>
          <x-action-message class="mr-3 mt-2 text-blue-600" on="upload_logo">
            Imagen Agregada
          </x-action-message>
        </div>

      </div>

      <div class="text-center">
        <p class="mt-3 text-xl text-gray-700">Logo actual</p>

        <img src="{{ Storage::url($current_logo) }}" class="ml-auto mr-auto" alt="logo_kuchaskids" width="300"
          height="150">
      </div>
    </div>

    <x-action-message class="mr-3 mt-2 text-blue-600" on="updated_subcategories_positions">
      Posiciones Actualizadas
    </x-action-message>
  </x-slot>
</x-action-section>
