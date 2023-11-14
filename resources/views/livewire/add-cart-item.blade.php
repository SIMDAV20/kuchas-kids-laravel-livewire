{{-- x-data le digo que puedo usar alphine en todo el div que lleva dentro --}}
<div x-data>
  {{-- TODO: borrar luego --}}
  {{ $quantity }}
  <div class="text-gray-550 my-4">
    <span class="font-semibold text-lg">Stock {{ $quantity > 0 ? '' : 'no' }} disponible</span>
    @if ($quantity)
      {{ $quantity < 10 ?: '' }}
    @endif
  </div>
  <div class="flex">
    <div class="mr-4">
      <x-secondary-button disabled x-bind:disabled="$wire.qty <= 1" wire:loading.attr="disabled" wire:target="decrement"
        wire:click="decrement">
        -
      </x-secondary-button>

      <span class="mx-2 text-gray-550">{{ $qty }}</span>

      <x-secondary-button disabled x-bind:disabled="$wire.qty >= $wire.quantity" wire:loading.attr="disabled"
        wire:target="increment" wire:click="increment">
        +
      </x-secondary-button>
    </div>
    <div class="flex-1">
      <x-button x-bind:disabled="$wire.qty > $wire.quantity" wire:click="addItem" wire:loading.attr="disabled"
        wire:target="addItem" color="violet" number="350" class="w-full">
        AGREGAR
      </x-button>
    </div>
  </div>
</div>
