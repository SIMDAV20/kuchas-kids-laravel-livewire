<div class="bg-white shadow-xl rounded-lg p-6">
    <p class="text-2xl text-center font-semibold mb-2">
        Estado del Producto
    </p>

    <div class="grid md:grid-cols-2 gap-6">

        <div class="flex">
            <label class="mr-6">
                <input wire:model.defer="status" type="radio" name="status" value="1" />
                Borrador
            </label>
            <label class="mr-6">
                <input wire:model.defer="status" type="radio" name="status" value="2" />
                Publicado
            </label>
        </div>

        <div class="flex justify-end items-center">
            <x-action-message class="mr-3" on="saved">
                Actualizado
            </x-action-message>

            <x-button wire:click="save" wire:loading.attr="disabled" wire:target="save">
                Actualizar
            </x-button>
        </div>
    </div>
</div>
