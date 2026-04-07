<x-app-layout>
    @livewire('product-detail', ['product' => $product])

    <div class="container pb-16">
        <h2 class="text-violet-350 text-2xl font-bold mb-4">Productos Relacionados</h2>
        <hr class="mb-8 border-gray-200">
        @livewire('category-products', ['category' => $product->subcategory->category, 'product' => $product])
    </div>
</x-app-layout>
