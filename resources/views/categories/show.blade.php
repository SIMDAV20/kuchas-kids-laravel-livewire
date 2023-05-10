<x-app-layout>
    <div class="container py-8">
        <figure class="mb-4">
            <img class="w-full object-contain object-center category-banner" src="{{ Storage::url($category->image)}}"
                alt="src=" {{ Storage::url($category->image)}}">
        </figure>

        @livewire('category-filter', ['category' => $category])
    </div>

</x-app-layout>