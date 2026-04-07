@props(['maxHeight' => '70vh', 'bgTable' => 'bg-white'])

<div class="flex flex-col {{ $bgTable }}">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow border-b border-gray-200 sm:rounded-lg overflow-y-auto custom-scrollbar" style="max-height: {{ $maxHeight }};">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
    /* Sticky header styles for nested tables */
    .custom-scrollbar table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f9fafb; /* bg-gray-50 */
    }
</style>
