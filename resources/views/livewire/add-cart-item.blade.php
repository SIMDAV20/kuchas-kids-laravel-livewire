<div x-data>
    {{-- Info de Stock --}}
    <div class="mb-6">
        @if($quantity > 0)
            <div class="flex items-center gap-2 text-green-600 font-bold text-sm">
                <i class="fas fa-check-circle"></i>
                <span>{{ $quantity }} unidades disponibles</span>
            </div>
        @else
            <div class="flex items-center gap-2 text-red-500 font-bold text-sm">
                <i class="fas fa-times-circle"></i>
                <span>Sin stock disponible actualmente</span>
            </div>
        @endif
    </div>

    {{-- Controles de Cantidad y Botón --}}
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex items-center self-start border-2 border-gray-200 rounded-xl overflow-hidden bg-gray-50">
            <button 
                x-bind:disabled="$wire.qty <= 1" 
                wire:click="decrement" 
                wire:loading.attr="disabled"
                class="px-4 py-3 text-gray-600 hover:bg-gray-200 transition-colors disabled:opacity-30">
                <i class="fas fa-minus text-xs"></i>
            </button>
            
            <span class="px-6 py-3 font-bold text-gray-800 tabular-nums min-w-[50px] text-center">
                {{ $qty }}
            </span>

            <button 
                x-bind:disabled="$wire.qty >= $wire.quantity" 
                wire:click="increment" 
                wire:loading.attr="disabled"
                class="px-4 py-3 text-gray-600 hover:bg-gray-200 transition-colors disabled:opacity-30">
                <i class="fas fa-plus text-xs"></i>
            </button>
        </div>

        <div class="flex-1">
            <button 
                wire:click="addItem" 
                wire:loading.attr="disabled" 
                @if($quantity <= 0) disabled @endif
                class="w-full h-full bg-violet-350 hover:bg-violet-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-violet-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 uppercase px-4">
                <i class="fas fa-shopping-bag"></i>
                <span>Añadir a la bolsa</span>
            </button>
        </div>
    </div>
</div>
