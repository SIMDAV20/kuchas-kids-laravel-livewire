<div class="container py-12">
    <x-form-section submit="save" class="mb-6">
        <x-slot name="title">
            Nuevo cupón
        </x-slot>
        <x-slot name="description">
            Crea un cupón de descuento por categoría, grupo de productos o envío gratis
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-3">
                <x-label>Código</x-label>
                <div class="flex gap-2 mt-1">
                    <x-input wire:model="createForm.code" type="text" class="w-full uppercase" />
                    <x-secondary-button type="button" wire:click="generateCode">
                        Generar
                    </x-secondary-button>
                </div>
                <x-input-error for="createForm.code" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label>Tipo de cupón</x-label>
                <select wire:model="createForm.type"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                    <option value="category">Categoría</option>
                    <option value="product_group">Grupo de productos</option>
                    <option value="shipping">Envío gratis</option>
                </select>
                <x-input-error for="createForm.type" />
            </div>

            @if ($createForm['type'] !== 'shipping')
                <div class="col-span-6 sm:col-span-3">
                    <x-label>Tipo de descuento</x-label>
                    <select wire:model="createForm.is_percentage"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                        <option value="1">Porcentaje (%)</option>
                        <option value="0">Monto fijo (S/)</option>
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-label>Valor</x-label>
                    <x-input wire:model="createForm.value" type="number" step="0.01" min="0" class="w-full mt-1" />
                    <x-input-error for="createForm.value" />
                </div>
            @endif

            <div class="col-span-6 sm:col-span-3">
                <x-label>Compra mínima (S/)</x-label>
                <x-input wire:model="createForm.min_purchase_amount" type="number" step="0.01" min="0"
                    class="w-full mt-1" />
                <x-input-error for="createForm.min_purchase_amount" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label>Límite de usos (opcional)</x-label>
                <x-input wire:model="createForm.usage_limit" type="number" min="1" class="w-full mt-1" />
                <x-input-error for="createForm.usage_limit" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label>Expira el (opcional)</x-label>
                <x-input wire:model="createForm.expires_at" type="date" class="w-full mt-1" />
                <x-input-error for="createForm.expires_at" />
            </div>

            @if ($createForm['type'] === 'category')
                <div class="col-span-6">
                    <x-label>Categorías aplicables</x-label>
                    <select wire:model="createForm.selectedCategories" multiple
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"
                        size="5">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($createForm['type'] === 'product_group')
                <div class="col-span-6">
                    <x-label>Productos aplicables</x-label>
                    <select wire:model="createForm.selectedProducts" multiple
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"
                        size="5">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                cupón creado
            </x-action-message>
            <x-button>
                Agregar
            </x-button>
        </x-slot>
    </x-form-section>

    <x-action-section>
        <x-slot name="title">
            Lista de cupones
        </x-slot>
        <x-slot name="description">
            Aquí encontrará todos los cupones creados
        </x-slot>
        <x-slot name="content">
            <table class="text-gray-600 w-full">
                <thead class="border-b border-gray-300">
                    <tr class="text-left">
                        <th class="py-2">Código</th>
                        <th class="py-2">Tipo</th>
                        <th class="py-2">Valor</th>
                        <th class="py-2">Usos</th>
                        <th class="py-2">Expira</th>
                        <th class="py-2">Estado</th>
                        <th class="py-2">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse ($coupons as $item)
                        <tr>
                            <td class="py-2 font-semibold">{{ $item->code }}</td>
                            <td class="py-2">
                                @switch($item->type)
                                    @case('category')
                                        Categoría
                                        @break
                                    @case('product_group')
                                        Productos
                                        @break
                                    @case('shipping')
                                        Envío gratis
                                        @break
                                @endswitch
                            </td>
                            <td class="py-2">
                                @if ($item->type !== 'shipping')
                                    {{ $item->is_percentage ? $item->value . '%' : 'S/ ' . number_format($item->value, 2) }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="py-2">
                                {{ $item->used_count }}{{ $item->usage_limit ? ' / ' . $item->usage_limit : '' }}
                            </td>
                            <td class="py-2">
                                {{ $item->expires_at ? $item->expires_at->format('d/m/Y') : 'Sin expiración' }}
                            </td>
                            <td class="py-2">
                                <a wire:click="toggleActive('{{ $item->id }}')"
                                    class="cursor-pointer font-semibold {{ $item->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                                </a>
                            </td>
                            <td class="py-2">
                                <div class="flex divide-x divide-gray-300 font-semibold">
                                    <a wire:click="edit('{{ $item->id }}')"
                                        class="pr-2 hover:text-blue-600 cursor-pointer">
                                        Editar
                                    </a>
                                    <a wire:click="$emit('deleteCoupon', '{{ $item->id }}')"
                                        class="pl-2 hover:text-red-600 cursor-pointer">
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-gray-400">
                                Aún no hay cupones creados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-slot>
    </x-action-section>

    {{-- Modal de edición --}}
    <x-dialog-modal wire:model="editForm.open">
        <x-slot name="title">
            Editar cupón
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-6 gap-4">
                <div class="col-span-6 sm:col-span-3">
                    <x-label>Código</x-label>
                    <x-input wire:model="editForm.code" type="text" class="w-full uppercase mt-1" />
                    <x-input-error for="editForm.code" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-label>Tipo de cupón</x-label>
                    <select wire:model="editForm.type"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                        <option value="category">Categoría</option>
                        <option value="product_group">Grupo de productos</option>
                        <option value="shipping">Envío gratis</option>
                    </select>
                    <x-input-error for="editForm.type" />
                </div>

                @if ($editForm['type'] !== 'shipping')
                    <div class="col-span-6 sm:col-span-3">
                        <x-label>Tipo de descuento</x-label>
                        <select wire:model="editForm.is_percentage"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                            <option value="1">Porcentaje (%)</option>
                            <option value="0">Monto fijo (S/)</option>
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <x-label>Valor</x-label>
                        <x-input wire:model="editForm.value" type="number" step="0.01" min="0"
                            class="w-full mt-1" />
                        <x-input-error for="editForm.value" />
                    </div>
                @endif

                <div class="col-span-6 sm:col-span-3">
                    <x-label>Compra mínima (S/)</x-label>
                    <x-input wire:model="editForm.min_purchase_amount" type="number" step="0.01" min="0"
                        class="w-full mt-1" />
                    <x-input-error for="editForm.min_purchase_amount" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-label>Límite de usos (opcional)</x-label>
                    <x-input wire:model="editForm.usage_limit" type="number" min="1" class="w-full mt-1" />
                    <x-input-error for="editForm.usage_limit" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-label>Expira el (opcional)</x-label>
                    <x-input wire:model="editForm.expires_at" type="date" class="w-full mt-1" />
                    <x-input-error for="editForm.expires_at" />
                </div>

                <div class="col-span-6 sm:col-span-3 flex items-center mt-6">
                    <label class="flex items-center">
                        <x-checkbox wire:model="editForm.is_active" />
                        <span class="ml-2 text-sm text-gray-600">Cupón activo</span>
                    </label>
                </div>

                @if ($editForm['type'] === 'category')
                    <div class="col-span-6">
                        <x-label>Categorías aplicables</x-label>
                        <select wire:model="editForm.selectedCategories" multiple
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"
                            size="5">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif ($editForm['type'] === 'product_group')
                    <div class="col-span-6">
                        <x-label>Productos aplicables</x-label>
                        <select wire:model="editForm.selectedProducts" multiple
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"
                            size="5">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('editForm.open', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="update" wire:loading.attr="disabled" wire:target="update">
                Actualizar
            </x-button>
        </x-slot>
    </x-dialog-modal>

    @push('scripts')
        <script>
            Livewire.on('deleteCoupon', couponId => {
                Swal.fire({
                    title: 'Esta seguro de eliminar el cupón?',
                    text: "Acción irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.coupon-component', 'delete', couponId);

                        Swal.fire(
                            'Eliminado!',
                            'El cupón ha sido eliminado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</div>
