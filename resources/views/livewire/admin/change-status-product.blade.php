<div class="flex items-center gap-2 justify-center">
    <x-checkbox id="checkbox-{{ $model }}-{{ $item->id }}" wire:model="prod_status" class="cursor-pointer"></x-checkbox>
    
    @php
        // Lógica unificada:
        // Si es Product: 2 es Publicado, 1 es Borrador
        // Si es Variant/Otros: true/1 es Publicado, false/0 es Borrador
        $isPublic = ($model == 'Product') ? ($item->status == 2) : (bool)$item->status;
    @endphp

    @if($isPublic)
        <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-black rounded-lg bg-green-100 text-green-700 uppercase tracking-tighter">
            Publicado
        </span>
    @else
        <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-black rounded-lg bg-red-100 text-red-700 uppercase tracking-tighter">
            Borrador
        </span>
    @endif
</div>
