<div>
    <div class="grid md:grid-cols-1 gap-4">

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
