<div class="space-y-6">
    {{-- Vista Previa de Imágenes Asignadas (Sortable) --}}
    @php $assigned = \App\Models\Image::whereIn('id', $assignedIds ?? [])->get()->sortBy(fn($img) => array_search($img->id, $assignedIds))->values(); @endphp
    <div x-data="sortableGallery" x-ref="sortableGrid"
         class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

        @foreach($assigned as $assignedImg)
            <div class="relative group aspect-square rounded-xl overflow-hidden border-2 border-indigo-100 shadow-sm cursor-grab active:cursor-grabbing"
                 wire:key="assigned-{{ $assignedImg->id }}"
                 data-id="{{ $assignedImg->id }}">
                <img src="{{ Storage::url($assignedImg->url) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105 pointer-events-none">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button wire:click="toggleImage({{ $assignedImg->id }})"
                            x-on:click.stop
                            class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 shadow-lg">
                        <i class="fas fa-unlink text-xs"></i>
                    </button>
                </div>
                <span class="absolute top-1 left-1 bg-indigo-500 text-white text-[10px] px-1.5 py-0.5 rounded font-black pointer-events-none">
                    #{{ $loop->iteration }}
                </span>
                <span class="absolute bottom-1 right-1 text-white/70 pointer-events-none">
                    <i class="fas fa-grip-dots text-[10px]"></i>
                </span>
            </div>
        @endforeach

        <button wire:click="$set('open_gallery', true)"
            class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl hover:border-indigo-400 hover:bg-indigo-50 transition-all text-gray-400 hover:text-indigo-600 group">
            <i class="fas fa-plus-circle text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
            <span class="text-[10px] font-bold uppercase tracking-widest">Biblioteca</span>
        </button>
    </div>

    {{-- MODAL DE BIBLIOTECA --}}
    <x-dialog-modal wire:model="open_gallery" maxWidth="4xl">
        <x-slot name="title">
            <span class="flex items-center">
                <i class="fas fa-photo-video mr-2 text-indigo-500"></i> Biblioteca de Medios
            </span>
        </x-slot>

        <x-slot name="content">
            {{-- Blocked images results --}}
            @if(count($blockedImages) > 0)
                <div class="mb-4 border border-red-200 bg-red-50 rounded-xl p-4">
                    <p class="text-xs font-black text-red-600 uppercase tracking-wide mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        {{ count($blockedImages) }} imagen(es) no se pudieron eliminar por estar en uso:
                    </p>
                    <ul class="space-y-2">
                        @php
                            $byProduct = [];
                            foreach ($blockedImages as $blocked) {
                                foreach ($blocked['products'] as $product) {
                                    $key = $product['link'];
                                    if (!isset($byProduct[$key])) {
                                        $byProduct[$key] = ['name' => $product['name'], 'link' => $product['link'], 'images' => []];
                                    }
                                    $byProduct[$key]['images'][] = $blocked['url'];
                                }
                            }
                        @endphp
                        @foreach($byProduct as $entry)
                            <li class="flex items-start gap-3 text-xs">
                                <div class="flex -space-x-2 flex-shrink-0">
                                    @foreach($entry['images'] as $imgUrl)
                                        <img src="{{ Storage::url($imgUrl) }}" class="w-8 h-8 rounded-full ring-2 ring-white object-cover border border-red-200">
                                    @endforeach
                                </div>
                                <div>
                                    <a href="{{ $entry['link'] }}" target="_blank"
                                       class="inline-flex items-center gap-1 font-bold text-indigo-600 hover:underline">
                                        {{ $entry['name'] }}
                                        <i class="fas fa-external-link-alt text-[8px]"></i>
                                    </a>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        {{ count($entry['images']) }} imagen(es) en uso
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <button wire:click="$set('blockedImages', [])" class="mt-3 text-[10px] text-gray-400 hover:text-gray-600 underline">
                        Cerrar aviso
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 min-h-[400px]">
                {{-- Panel Lateral: Subida --}}
                <div class="md:col-span-1 border-r border-gray-100 pr-4">
                    <div class="sticky top-0 space-y-4">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Subir Imagen</h4>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                            <x-file-attachment wire:model="image" :file="$image" mode="profile"
                                profile-class="w-full h-32 rounded-lg" accept="image/jpg,image/jpeg,image/png" />
                            @if($image)
                                <x-button wire:click="uploadImage" class="mt-4 w-full text-xs">
                                    Subir a Biblioteca
                                </x-button>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-400 leading-tight italic">Las fotos subidas se añadirán al pool compartido.</p>
                    </div>
                </div>

                {{-- Panel Central: Pool Global --}}
                <div class="md:col-span-3">
                    @livewire('admin.image-library', ['item_id' => $item_id, 'model' => $model], key('lib-' . $item_id . '-' . $model))
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open_gallery', false)">Asignar</x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sortableGallery', () => ({
            init() {
                Sortable.create(this.$refs.sortableGrid, {
                    animation: 150,
                    ghostClass: 'opacity-30',
                    filter: 'button, a',
                    preventOnFilter: false,
                    onEnd: () => {
                        const ids = [...this.$refs.sortableGrid.querySelectorAll('[data-id]')]
                            .map(el => el.dataset.id);
                        this.$wire.reorderImages(ids);
                    }
                });
            }
        }))
    })
</script>
@endpush
