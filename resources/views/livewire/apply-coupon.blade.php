<div>
    <p class="text-sm font-semibold text-gray-700 mb-2">Cupón de descuento</p>

    <div class="flex gap-2">
        <input
            type="text"
            wire:model.defer="coupon_code"
            {{ $applied ? 'disabled' : '' }}
            placeholder="Ingresa tu código"
            class="form-control flex-1 uppercase {{ $applied ? 'bg-gray-100 text-gray-500' : '' }}"
        />

        @if (! $applied)
            <button
                wire:click="apply"
                wire:loading.attr="disabled"
                class="px-4 py-2 bg-violet-600 text-white text-sm font-semibold rounded hover:bg-violet-700 disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="apply">Aplicar</span>
                <span wire:loading wire:target="apply">...</span>
            </button>
        @else
            <button
                wire:click="remove"
                class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded hover:bg-gray-300"
            >
                Quitar
            </button>
        @endif
    </div>

    @if ($message)
        <p class="mt-2 text-sm font-medium {{ $message_type === 'success' ? 'text-green-600' : 'text-red-600' }}">
            {{ $message }}
        </p>
    @endif
</div>
