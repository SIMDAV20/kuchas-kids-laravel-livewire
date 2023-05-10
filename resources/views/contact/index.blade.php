<x-app-layout>
    <div class="container py-8 h-screen">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="mb-4 font-bold text-gray-500">CONTÁCTANOS</h1>
            <div class="mb-4">
                <x-label value="Nombre de contacto" />
                {{-- wire:model.defer una vez continue con el form  --}}
                <x-input type="text" wire:model.defer="contact"
                    placeholder="Ingrese el nombre de la persona que recibirá el producto" class="w-full" />
                <x-input-error for="contact" />
            </div>

            <div class="mb-4">
                <x-label value="Teléfono de contacto" />
                <x-input type="text" wire:model.defer="phone" placeholder="Ingrese el númer de teléfono de contacto"
                    class="w-full" />
                <x-input-error for="phone" />
            </div>
        </div>
    </div>
</x-app-layout>
