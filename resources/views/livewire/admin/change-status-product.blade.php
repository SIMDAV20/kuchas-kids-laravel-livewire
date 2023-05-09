<div>
    <x-checkbox id="checkbox-{{ $item->id }}" wire:model="prod_status"></x-checkbox>
    @switch($item->status)
        @case(1)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                Borrador
            </span>
        @break

        @case(2)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Publicado
            </span>
        @break

        @default
    @endswitch
</div>
