{{-- php artisan make:component AdminLayout --}}
{{-- en app/view/component se crea el adminLayout --}}
<x-admin-layout>
    <div class="container py-12">
        @livewire('admin.create-category')
    </div>

@push('scripts')
        <script>
            Livewire.on('deleteCategory', categorySlug => {
                Swal.fire({
                    title: 'Esta seguro de eliminar el registro?',
                    text: "Acción irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.create-category', 'delete', categorySlug);

                        Swal.fire(
                            'Eliminado!',
                            'El resgistro ha sido eliminado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</x-admin-layout>
