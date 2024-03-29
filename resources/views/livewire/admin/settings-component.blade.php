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

  @livewire('admin.update-logo-image')

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
