<div>
    {{-- Toolbar --}}
    <div class="flex items-center gap-3 mb-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
            <input type="text" wire:model.debounce.400ms="search"
                placeholder="Buscar por nombre..."
                class="w-full pl-8 pr-4 py-2 text-xs border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <label class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 cursor-pointer whitespace-nowrap">
            <input type="checkbox" wire:model="onlyAssigned" class="rounded border-gray-300 text-indigo-600">
            Solo asignadas
        </label>
    </div>

    {{-- Bulk delete bar --}}
    @if(count($selectedIds) > 0)
        <div class="flex items-center gap-3 mb-3 px-3 py-2 bg-red-50 border border-red-200 rounded-lg">
            <span class="text-xs font-bold text-red-600">{{ count($selectedIds) }} seleccionada(s)</span>
            <button
                x-on:click="Swal.fire({
                    title: 'Eliminar imágenes',
                    text: '{{ count($selectedIds) }} imagen(es) serán eliminadas si no están en uso.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(r => { if (r.isConfirmed) $wire.requestDelete($wire.selectedIds) })"
                class="text-[10px] font-black uppercase text-red-500 border border-red-300 px-3 py-1 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-trash-alt mr-1"></i> Eliminar seleccionadas
            </button>
            <button wire:click="$set('selectedIds', [])"
                class="text-[10px] text-gray-400 hover:text-gray-600 ml-auto">
                Limpiar
            </button>
        </div>
    @endif

    {{-- Grid --}}
    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
        @forelse($images as $img)
            @php
                $isAssigned  = in_array((string)$img->id, $assignedIds) || in_array((int)$img->id, $assignedIds);
                $isSelected  = in_array((string)$img->id, $selectedIds) || in_array((int)$img->id, $selectedIds);
            @endphp
            <div wire:key="lib-img-{{ $img->id }}"
                 class="relative aspect-square cursor-pointer rounded-lg overflow-hidden border-2 transition-all group
                        {{ $isSelected  ? 'border-red-400 ring-2 ring-red-100'
                          : ($isAssigned ? 'border-indigo-500 shadow-md ring-2 ring-indigo-100'
                                        : 'border-transparent opacity-80 hover:opacity-100') }}">

                <img wire:click="toggleImage({{ $img->id }})"
                     src="{{ Storage::url($img->url) }}"
                     class="w-full h-full object-cover">

                {{-- Assigned badge --}}
                @if($isAssigned && !$isSelected)
                    <div class="absolute inset-0 bg-indigo-500/20 flex items-center justify-center pointer-events-none">
                        <div class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg scale-110">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                    </div>
                @endif

                {{-- Selected overlay --}}
                @if($isSelected)
                    <div class="absolute inset-0 bg-red-400/20 flex items-center justify-center pointer-events-none">
                        <div class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                            <i class="fas fa-times text-xs"></i>
                        </div>
                    </div>
                @endif

                {{-- Hover actions --}}
                <div class="absolute bottom-0 inset-x-0 flex justify-between items-center px-1 py-1
                            opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-black/50 to-transparent">
                    {{-- Checkbox select for delete --}}
                    <input type="checkbox"
                           value="{{ $img->id }}"
                           wire:model="selectedIds"
                           class="rounded border-white/70 text-red-500 focus:ring-red-400 cursor-pointer"
                           x-on:click.stop>
                    {{-- Single delete --}}
                    <button
                        x-on:click.stop="Swal.fire({
                            title: 'Eliminar imagen',
                            text: 'Se eliminará si no está en uso.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then(r => { if (r.isConfirmed) $wire.requestDelete([{{ $img->id }}]) })"
                        class="w-6 h-6 bg-white/80 hover:bg-white text-red-500 rounded flex items-center justify-center">
                        <i class="fas fa-trash-alt text-[10px]"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-5 text-center py-8 text-gray-400 text-xs italic">
                No se encontraron imágenes.
            </div>
        @endforelse
    </div>

    {{-- Load more --}}
    @if($hasMore)
        <div class="mt-3 text-center">
            <button wire:click="loadMore" wire:loading.attr="disabled"
                class="text-[10px] font-black uppercase tracking-widest text-indigo-500 border border-indigo-200 px-4 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors disabled:opacity-50">
                <span wire:loading.remove wire:target="loadMore">Cargar más</span>
                <span wire:loading wire:target="loadMore">Cargando...</span>
            </button>
        </div>
    @endif
</div>
