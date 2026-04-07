<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="text-center border-b pb-6 mb-6">
            <h1 class="text-3xl font-bold text-violet-600 mb-2">LIBRO DE RECLAMACIONES</h1>
            <p class="text-gray-600">En cumplimiento de lo dispuesto por el Código de Protección y Defensa del Consumidor, La Tiendita de Madaí pone a disposición de sus clientes el presente Libro de Reclamaciones virtual.</p>
        </div>

        @if($successMessage)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-center" role="alert">
                <strong class="font-bold">¡Enviado exitosamente!</strong>
                <span class="block sm:inline">Hemos recibido su registro y le daremos respuesta en un plazo máximo de 15 días hábiles a través del medio de contacto proporcionado.</span>
            </div>
            <div class="text-center">
                <button wire:click="$set('successMessage', false)" class="bg-violet-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-violet-700 transition">
                    Volver al formulario
                </button>
            </div>
        @else

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-violet-50 p-4 rounded-lg">
                <h3 class="font-semibold text-violet-800 mb-2 border-b border-violet-200 pb-1">Datos del proveedor:</h3>
                <ul class="text-sm text-gray-700 space-y-1 mt-2">
                    <li><span class="font-medium">Razón social:</span> Kelita Madaí Bautista Delgado</li>
                    <li><span class="font-medium">Nombre comercial:</span> La Tiendita de Madaí</li>
                    <li><span class="font-medium">RUC:</span> 10400255453</li>
                </ul>
            </div>
            <div class="bg-violet-50 p-4 rounded-lg">
                <h3 class="font-semibold text-violet-800 mb-2 border-b border-violet-200 pb-1">Canal de atención:</h3>
                <p class="text-sm text-gray-700 mt-2">Para registrar un reclamo, puedes hacerlo a través de este formulario o comunicarte mediante el canal oficial:</p>
                <div class="mt-2 font-medium text-violet-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 2C6.486 2 2 6.486 2 12c0 1.846.505 3.593 1.458 5.116L2 22l4.981-1.39A9.957 9.957 0 0012.031 22c5.542 0 10-4.486 10-10s-4.458-10-10-10zm0 18.254c-1.579 0-3.136-.425-4.5-1.229l-.323-.191-3.344.933.95-3.255-.21-.334c-.878-1.393-1.341-2.998-1.341-4.664C3.263 7.437 6.993 3.707 11.448 3.707c4.454 0 8.184 3.73 8.184 8.184 0 4.453-3.73 8.184-8.184 8.184zm4.49-6.143c-.246-.123-1.458-.72-1.684-.803-.226-.083-.391-.123-.556.123-.165.246-.638.803-.781.966-.143.164-.287.185-.533.062-.246-.123-1.04-.383-1.982-1.221-.733-.65-1.228-1.455-1.371-1.701-.143-.246-.015-.379.108-.502.11-.11.246-.288.369-.431.123-.144.164-.246.246-.41.082-.164.041-.308-.021-.43-.062-.123-.556-1.343-.761-1.838-.2-.486-.403-.42-.556-.428-.143-.008-.308-.008-.473-.008-.165 0-.432.062-.658.308-.226.246-.864.843-.864 2.054 0 1.21.884 2.378 1.008 2.542.123.164 1.731 2.64 4.193 3.705.586.252 1.043.402 1.399.515.588.187 1.123.16 1.547.097.474-.07 1.458-.596 1.664-1.171.206-.575.206-1.069.144-1.171-.062-.102-.226-.164-.472-.287z"></path></svg>
                    WhatsApp: 960 546 859
                </div>
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6 text-gray-700">
            <!-- Sección 1 -->
            <div class="border border-gray-200 rounded-lg p-6 relative">
                <h2 class="text-sm font-bold text-white bg-violet-600 px-3 py-1 rounded absolute -top-3 left-4">1. Identificación del Consumidor</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre completo <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="fullName" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Ej. Juan Pérez">
                        @error('fullName') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">DNI / CE <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="documentId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Número de documento">
                        @error('documentId') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Teléfono <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Número de contacto">
                        @error('phone') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                        <input type="email" wire:model.defer="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="correo@ejemplo.com">
                        @error('email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Sección 2 -->
            <div class="border border-gray-200 rounded-lg p-6 relative">
                <h2 class="text-sm font-bold text-white bg-violet-600 px-3 py-1 rounded absolute -top-3 left-4">2. Detalle del Reclamo o Queja</h2>
                
                <div class="mt-4 mb-4">
                    <label class="block text-sm font-medium mb-2">Tipo <span class="text-red-500">*</span></label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center">
                            <input type="radio" wire:model.defer="type" value="Reclamo" class="text-violet-600 focus:ring-violet-500">
                            <span class="ml-2">Reclamo</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.defer="type" value="Queja" class="text-violet-600 focus:ring-violet-500">
                            <span class="ml-2">Queja</span>
                        </label>
                    </div>
                    @error('type') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    
                    <div class="mt-3 bg-gray-50 border border-gray-200 p-3 rounded text-xs text-gray-500">
                        <p><strong>Reclamo:</strong> Disconformidad relacionada con los productos o servicios.</p>
                        <p><strong>Queja:</strong> Disconformidad no relacionada directamente con el producto o servicio, o malestar respecto a la atención.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Número de pedido (opcional)</label>
                        <input type="text" wire:model.defer="orderNumber" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Ej. #10045">
                        @error('orderNumber') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Monto reclamado (S/.) (de ser el caso)</label>
                        <input type="number" step="0.01" wire:model.defer="claimedAmount" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="0.00">
                        @error('claimedAmount') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Descripción del hecho <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Detalle los hechos..."></textarea>
                    @error('description') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Sección 3 -->
            <div class="border border-gray-200 rounded-lg p-6 relative">
                <h2 class="text-sm font-bold text-white bg-violet-600 px-3 py-1 rounded absolute -top-3 left-4">3. Pedido del Consumidor</h2>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Indicar claramente lo que solicita (cambio, devolución, información, etc.) <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="consumerRequest" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Escriba aquí su solicitud..."></textarea>
                    @error('consumerRequest') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Información Legal -->
            <div class="text-xs text-gray-500 space-y-4 pt-4 border-t border-gray-200">
                <p><strong>Condiciones:</strong> La Tiendita de Madaí dará respuesta a su reclamo o queja en un plazo máximo de 15 días hábiles, el cual no será prorrogable. La respuesta será brindada a través del medio de contacto proporcionado por el consumidor.</p>
                <p>El registro de un reclamo o queja no impide acudir a otras vías de solución de controversias ni constituye un requisito previo para interponer una denuncia ante la autoridad competente.</p>
                <p><strong>Protección de datos:</strong> Los datos personales proporcionados en este formulario serán utilizados únicamente para la gestión y atención del reclamo o queja, conforme a la normativa vigente en materia de protección de datos personales.</p>
            </div>

            <div class="pt-6 flex justify-center">
                <button type="submit" class="bg-violet-600 outline-none hover:bg-violet-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 w-full md:w-auto text-center" wire:loading.attr="disabled">
                    <span wire:loading.remove>Enviar Reclamo/Queja</span>
                    <span wire:loading>Enviando...</span>
                </button>
            </div>
            <p class="text-center text-xs text-gray-400 mt-2">* Campos obligatorios</p>
        </form>
        @endif
    </div>
</div>
