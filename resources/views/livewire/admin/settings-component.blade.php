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

  <x-form-section submit="update" class="mb-6">
    <x-slot name="title">
      Información de Contacto
    </x-slot>
    <x-slot name="description">
      En esta sección podrá configurar el número de WhatsApp y los correos electrónicos para recibir mensajes y de atención al cliente.
    </x-slot>
    <x-slot name="form">
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          WhatsApp
        </x-label>
        <x-input wire:model.trim="editForm.whatsapp" type="text" class="w-full mt-1" />
        <x-input-error for="editForm.whatsapp" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Correo electrónico (Recibir mensajes)
        </x-label>
        <x-input wire:model.trim="editForm.email_receive" type="email" class="w-full mt-1" />
        <x-input-error for="editForm.email_receive" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Correo electrónico (Atención al cliente)
        </x-label>
        <x-input wire:model.trim="editForm.email_client" type="email" class="w-full mt-1" />
        <x-input-error for="editForm.email_client" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Razón social
        </x-label>
        <x-input wire:model.trim="editForm.business_name" type="text" class="w-full mt-1" />
        <x-input-error for="editForm.business_name" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          Nombre comercial
        </x-label>
        <x-input wire:model.trim="editForm.trade_name" type="text" class="w-full mt-1" />
        <x-input-error for="editForm.trade_name" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <x-label>
          RUC
        </x-label>
        <x-input wire:model.trim="editForm.ruc" type="text" class="w-full mt-1" />
        <x-input-error for="editForm.ruc" />
      </div>
    </x-slot>
    <x-slot name="actions">
      <x-action-message class="mr-3" on="saved">
        Información actualizada
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
