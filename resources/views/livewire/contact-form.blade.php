<div class="container py-8 min-h-screen">
    <div class="grid md:grid-cols-1 lg:grid-cols-3 lg:gap-4">
        <form wire:submit.prevent="submit" class="bg-white rounded-lg col-span-2 shadow p-6 mb-4">
            <h1 class="mb-4 font-semibold text-pink-500 text-3xl">CONTÁCTANOS</h1>
            <div class="mb-4">
                <x-label value="Nombre de contacto" />
                {{-- wire:model.defer una vez continue con el form  --}}
                <x-input type="text" wire:model.defer="contact"
                    placeholder="Ingrese el nombre de la persona que recibirá el producto" class="w-full" />
                <x-input-error for="contact" />
            </div>

            <div class="mb-4">
                <x-label value="Correo de contacto" />
                <x-input type="text" wire:model.defer="email" placeholder="Ingrese su correo electrónico"
                    class="w-full" />
                <x-input-error for="email" />
            </div>

            <div class="mb-4">
                <x-label value="Teléfono de contacto o Whatsapp" />
                <x-input type="text" wire:model.defer="phone" placeholder="Ingrese el número de teléfono o whatsapp"
                    class="w-full" />
                <x-input-error for="phone" />
            </div>

            <div class="mb-4">
                <x-label value="Mensaje" />
                <x-textarea wire:model="message" placeholder="Ingrese un mensaje" />
                <x-input-error for="message" />
            </div>

            <x-button wire.loading.attr="disabled" wire.target="send_message" class="mb-4">
                Enviar Mensaje
            </x-button>
        </form>
        <div class="bg-white rounded-lg shadow col-span-1 p-6 mb-4">
            <div class="flex flex-col">
                <h3 class="text-lg text-gray-600 font-semibold">Nuestro horario de atención:</h3>

                <p class="mb-4">Lunes a viernes de 9:00am a 6:00pm, sábado 9:00am a 2:00pm</p>

                <span class="mb-4">
                    <h3 class="text-lg text-gray-600 font-semibold">Correo:</h3>
                    <a href="mailto:atencionalcliente@kuchaskids.pe" class="text-blue-600 hover:text-blue-400 mb-4">
                        atencionalcliente@kuchaskids.pe
                    </a>
                </span>
                <span>
                    <h3 class="text-lg text-gray-600 font-semibold">Celulares:</h3>
                    <a href="telf:960546859" class="text-blue-600 hover:text-blue-400 mb-4">960-546-859</a> /
                    <a href="telf:965394560" class="text-blue-600 hover:text-blue-400 mb-4">965-394-560</a>
                </span>
            </div>
        </div>
    </div>
</div>
