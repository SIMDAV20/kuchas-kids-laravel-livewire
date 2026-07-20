@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 pt-4 flex items-start justify-between gap-4">
        <div class="text-lg font-medium text-gray-900">
            {{ $title }}
        </div>
        <button type="button" x-on:click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="px-6 py-4 mt-2 text-sm text-gray-600 max-h-[calc(80vh-25px)] overflow-y-auto">
        {{ $content }}
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
        {{ $footer }}
    </div>
</x-modal>
