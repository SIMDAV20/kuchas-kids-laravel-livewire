<x-app-layout>

    <div class="container py-4 min-h-screen">

        <div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-20 m-auto">
            {{-- <div class="flex justify-center md:justify-end -mt-16">
                <img class="w-20 h-20 object-cover rounded-full border-2 border-indigo-500"
                    src="https://images.unsplash.com/photo-1499714608240-22fc6ad53fb2?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=334&q=80">
            </div> --}}
            <div>
                <img src="{{ asset('img/403.jpg')}}" alt="403.jpg">
            </div>
            <div>
                {{-- <h2 class="text-gray-800 text-3xl font-semibold">Error 404</h2> --}}
                <p class="mt-2 text-gray-600">Oops! acaba de entrar a una página no autorizada</p>
            </div>
            <div class="flex justify-end mt-4">
                <a href="{{ route('welcome') }}"
                    class="text-xl font-medium text-indigo-500 hover:text-indigo-700"
                >
                    Ir al inicio
                </a>
            </div>
        </div>
    </div>

</x-app-layout>
