<div class="space-y-6">
    {{-- Vista Previa de Imágenes Asignadas --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @php $assigned = \App\Models\Image::whereIn('id', $assignedIds ?? [])->get()->sortBy(fn($img) => array_search($img->id, $assignedIds)); @endphp
        
        @foreach($assigned as $assignedImg)
            <div class="relative group aspect-square rounded-xl overflow-hidden border-2 border-indigo-100 shadow-sm" wire:key="assigned-{{ $assignedImg->id }}">
                <img src="{{ Storage::url($assignedImg->url) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button wire:click="toggleImage({{ $assignedImg->id }})" class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 shadow-lg">
                        <i class="fas fa-unlink text-xs"></i>
                    </button>
                    <span class="absolute top-2 left-2 bg-indigo-500 text-white text-[10px] px-2 py-0.5 rounded font-black">#{{ array_search($assignedImg->id, $assignedIds) + 1 }}</span>
                </div>
            </div>
        @endforeach

        {{-- Botón de Abrir Biblioteca --}}
        <button wire:click="$set('open_gallery', true)" 
            class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl hover:border-indigo-400 hover:bg-indigo-50 transition-all text-gray-400 hover:text-indigo-600 group">
            <i class="fas fa-plus-circle text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
            <span class="text-[10px] font-bold uppercase tracking-widest">Biblioteca</span>
        </button>
    </div>

    {{-- MODAL DE BIBLIOTECA (MEDIOS) --}}
    <x-dialog-modal wire:model="open_gallery" maxWidth="4xl">
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <span class="flex items-center"><i class="fas fa-photo-video mr-2 text-indigo-500"></i> Biblioteca de Medios</span>
                <div class="flex items-center gap-4 mr-8">
                    <label class="flex items-center text-xs font-bold text-gray-500 cursor-pointer">
                        <input type="checkbox" wire:model="only_assigned" class="rounded mr-2 border-gray-300 text-indigo-600">
                        Solo seleccionadas
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
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
                        <p class="text-[10px] text-gray-400 leading-tight italic">Las fotos subidas se añadirán al pool compartido y se asignarán a este producto automáticamente.</p>
                    </div>
                </div>

                {{-- Panel Central: El Pool Global --}}
                <div class="md:col-span-3">
                    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($library as $libImage)
                            @php $isAssigned = in_array((string)$libImage->id, $assignedIds) || in_array((int)$libImage->id, $assignedIds); @endphp
                            <div wire:key="lib-{{ $libImage->id }}" 
                                 wire:click="toggleImage({{ $libImage->id }})"
                                 class="relative aspect-square cursor-pointer rounded-lg overflow-hidden border-2 transition-all {{ $isAssigned ? 'border-indigo-500 shadow-md ring-2 ring-indigo-100' : 'border-transparent opacity-80 hover:opacity-100' }}">
                                <img src="{{ Storage::url($libImage->url) }}" class="w-full h-full object-cover">
                                @if($isAssigned)
                                    <div class="absolute inset-0 bg-indigo-500/20 flex items-center justify-center">
                                        <div class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg transform scale-110">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                    </div>
                                @endif
                                <button wire:click.stop="confirmDelete({{ $libImage->id }})" class="absolute top-1 right-1 w-5 h-5 bg-white/80 hover:bg-white text-red-500 rounded-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open_gallery', false)">Terminar</x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</div>
