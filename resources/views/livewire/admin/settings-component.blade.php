<div class="container py-12">
    <x-form-section submit="update" class="mb-6">
        <x-slot name="title">
            Actualizar mínimo monto de venta
        </x-slot>
        <x-slot name="description">
            En esta sección podrá configurar el monto minímo para que el comprador o usuario tenga el costo de envío
            gratis,
            como tiene texto antes y despues del monto, entonces se ha divido en dos partes.
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Mostrar cintillo
                    <x-checkbox wire:model="show_headband" />
                </x-label>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Texto del cintillo parte 1
                </x-label>
                <x-input wire:model.trim="editForm.headband_one" type="text" class="w-full mt-1" />
                <x-input-error for="editForm.headband_one" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Monto mínimo
                </x-label>
                <x-input wire:model.trim="editForm.min_amount" type="number" step="0.1" pattern="^\d*(\.\d{0,2})?$"
                    class="w-full mt-1" />

                <x-input-error for="editForm.min_amount" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Texto del cintillo parte 2
                </x-label>
                <x-input wire:model.trim="editForm.headband_two" type="text" class="w-full mt-1" />

                <x-input-error for="editForm.headband_two" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                Monto actualizado
            </x-action-message>
            <x-button>
                Actualizar
            </x-button>
        </x-slot>
    </x-form-section>

    {{-- <x-form-section submit="update" class="mb-6">
        <x-slot name="title">
            Administrar Banners
        </x-slot>
        <x-slot name="description">
            En esta sección podrá agregar todos los banners
            va a depender tambien de la resolución, y no pasarlo por whatssap u otro aplicativo porque puede
            bajar
            la calidad de la imagen.
            <br>
            Se recomienda no subir imágenes tan pesadas ya que podría cargar la página más lenta.
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label>
                    Subir Imágenes
                </x-label>
                <x-input wire:model="banners" type="file" class="w-full mt-1 bg-gray-50" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG o JPG(MAX.
                    800x400px).</p>
                <x-input-error for="banners" />
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                Monto actualizado
            </x-action-message>
            <x-button>
                Actualizar
            </x-button>
        </x-slot>
    </x-form-section> --}}

    @push('scripts')
        <script>
            Livewire.on('show_headband', mensaje => {
                Swal.fire({
                    icon: 'success',
                    title: 'Acción realizada con éxito',
                    text: 'El cintillo se ' + mensaje,
                })
            })
        </script>
    @endpush
</div>
