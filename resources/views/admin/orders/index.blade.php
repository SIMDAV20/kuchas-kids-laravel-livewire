<x-admin-layout>
    <div class="container px-10 py-12 min-h-screen mx-4 ml-auto mr-auto">
        <section class="grid md:grid-cols-5 gap-6 text-white">

            <a href="{{ route('admin.orders.index') . '?status=1' }}"
                class="bg-red-500 bg-opacity-90 rounded-lg pt-8 pb-4">
                <p class="text-center text-2xl">
                    {{ $pendiente }}
                </p>
                <p class="uppercase text-center">Pendientes</p>
                <p class="text-center text-2xl mt-4">
                    <i class="fas fa-business-time"></i>
                </p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=2' }}"
                class="bg-gray-500 bg-opacity-90 rounded-lg pt-8 pb-4">
                <p class="text-center text-2xl">
                    {{ $recibido }}
                </p>
                <p class="uppercase text-center">Recibidos</p>
                <p class="text-center text-2xl mt-4">
                    <i class="fas fa-credit-card"></i>
                </p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=3' }}"
                class="bg-yellow-500 bg-opacity-90 rounded-lg pt-8 pb-4">
                <p class="text-center text-2xl">
                    {{ $enviado }}
                </p>
                <p class="uppercase text-center">Enviados</p>
                <p class="text-center text-2xl mt-4">
                    <i class="fas fa-truck"></i>
                </p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=4' }}"
                class="bg-pink-500 bg-opacity-90 rounded-lg pt-8 pb-4">
                <p class="text-center text-2xl">
                    {{ $entregado }}
                </p>
                <p class="uppercase text-center">Entregados</p>
                <p class="text-center text-2xl mt-4">
                    <i class="fas fa-check-circle"></i>
                </p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=5' }}"
                class="bg-green-500 bg-opacity-90 rounded-lg pt-8 pb-4">
                <p class="text-center text-2xl">
                    {{ $anulado }}
                </p>
                <p class="uppercase text-center">Anulados</p>
                <p class="text-center text-2xl mt-4">
                    <i class="fas fa-times-circle"></i>
                </p>
            </a>
        </section>

        @livewire('admin.show-orders', ['status' => $status])

    </div>
</x-admin-layout>
