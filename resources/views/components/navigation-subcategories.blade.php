@props(['category'])

<div class="grid grid-cols-4 p-4">
    @if (count($category->subcategories))
        <div>
            <p class="text-lg font-bold text-center text-gray-550 mb-3">Subcategorías</p>
            <ul>
                @foreach ($category->subcategories as $subcategory)
                    <li class="mb-4">
                        <a href="{{ route('categories.show', $category) . '?subcategoria=' . $subcategory->slug }}"
                            class="font-semibold inline-block py-1 px-4 text-gray-550 hover:text-violet-350 ">
                            {{ $subcategory->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-span-3">
            <img class="h-64 w-full object-cover object-center" src="{{ Storage::url($category->image) }}"
                alt="{{ Storage::url($category->image) }}">
        </div>
    @else
        <div class="col-span-4">
            <img class="h-64 w-full object-cover object-center" src="{{ Storage::url($category->image) }}"
                alt="{{ Storage::url($category->image) }}">
        </div>
    @endif

</div>
