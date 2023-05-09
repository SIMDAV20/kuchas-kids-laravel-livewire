<footer class="body-font bg-white footer-public">
    <a href="https://wa.me/51960546859?text=¡Hola%20*Kuchaskids.pe*!" class="whatsapp" target="_blank">
        <i class="fab fa-whatsapp whatsapp-icon"></i>
    </a>
    <div
        class="container px-5 py-24 mx-auto flex md:items-center lg:items-start md:flex-row md:flex-nowrap flex-wrap flex-col">
        <div class="sm:flex md:hidden mb-6">
            <h2 class="title-font font-bold text-center text-blue-900 tracking-widest text-sm mb-3">REDES SOCIALES</h2>
            <ul class="flex justify-center w-full">
                <li class="mr-3">
                    <a href="https://www.facebook.com/KuchasKids" target="_blank"
                        class="rounded-full h-16 w-16 bg-blue-400 text-white justify-center items-center flex text-3xl">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/kuchaskids/" target="_blank"
                        class="rounded-full h-16 w-16 bg-blue-400 text-white justify-center items-center flex text-3xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                </li>
            </ul>
        </div>
        {{-- <div class="w-64 flex-shrink-0 md:mx-0 mx-auto text-center md:text-left">
            <a href="" class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900">
                <i class="fas fa-cubes fa-lg text-purple-500"></i>
                <span class="ml-3 text-xl">Tailwind elements</span>
            </a>
            <p class="mt-2 text-sm text-gray-500">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi,
                quam?</p>
        </div> --}}
        <div class="flex-grow flex flex-wrap md:pl-20 -mb-10 md:mt-0 mt-10 md:text-left text-center">
            <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                <h2 class="title-font font-bold text-violet-350 tracking-widest text-sm mb-3">CATEGORÍAS</h2>
                <nav class="list-none mb-10">
                    @foreach ($categories as $category)
                        <li class="mb-3">
                            <a href="{{ route('categories.show', $category->slug) }}"
                                class="text-gray-550 hover:text-gray-400">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </nav>
            </div>
            <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                <h2 class="title-font font-bold text-violet-350 tracking-widest text-sm mb-3">SERVICIO AL CLIENTE</h2>
                <nav class="list-none mb-10">
                    {{-- <li class="mb-3">
                        <a href="" class="text-gray-800 hover:text-gray-400">Envíos</a>
                    </li>
                    <li class="mb-3">
                        <a href="" class="text-gray-800 hover:text-gray-400">Medios de Pago</a>
                    </li> --}}
                    <li class="mb-3">
                        <a href="{{ route('frequent-questions') }}"
                            class="text-gray-550 hover:text-gray-400">Preguntas Frecuentes</a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('shipping-policies') }}" class="text-gray-550 hover:text-gray-400">Políticas
                            de Envío</a>
                    </li>
                    {{-- <li class="mb-3">
                        <a href="" class="text-gray-800 hover:text-gray-400">Cambios y Devoluciones</a>
                    </li> --}}
                    <li class="mb-3">
                        <a href="{{ route('contact.index') }}"
                            class="text-gray-550 hover:text-gray-400">Contáctanos</a>
                    </li>
                </nav>
            </div>
            <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                <h2 class="title-font font-bold text-violet-350 tracking-widest text-sm mb-3">INFORMACIÓN DE UTILIDAD</h2>
                <nav class="list-none mb-10">
                    {{-- <li class="mb-3">
                        <a href="{{ route('about-us') }}" class="text-gray-800 hover:text-gray-400">Sobre Nosotros</a>
                    </li> --}}
                    <li class="mb-3">
                        <a href="{{ route('terms-and-conditions') }}"
                            class="text-gray-550 hover:text-gray-400">Términos y Condiciones</a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('returns-exchanges') }}" class="text-gray-550 hover:text-gray-400">Cambios y
                            devoluciones</a>
                    </li>
                </nav>
            </div>
            <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                <h2 class="title-font font-bold text-violet-350 tracking-widest text-sm mb-3">MÉTODOS DE PAGO</h2>
                <nav class="list-none mb-10">
                    <li class="mb-3">
                        <span class="text-gray-550">Depósito</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-gray-550">Transferencia</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-gray-550">Yape</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-violet-350">TODAS LAS TARJETAS</span>
                        <div class="mt-4">
                            <div style="background-color: #FF4240" class="p-1 mb-2">
                                <img src="{{ asset('img/Izipaylogo.png') }}" width="200" class="ml-auto mr-auto"
                                    alt="izipaylogo">
                            </div>
                            <img src="{{ asset('img/tipo_de_tarjetas.jpg') }}" alt="">
                        </div>
                    </li>
                </nav>
            </div>
        </div>
    </div>
    <div class="bg-gray-100 h-auto">
        <div class="container mx-auto py-4 px-5 flex flex-wrap flex-col sm:flex-row">
            <p class="text-gray-500 text-sm text-center sm:text-left">© {{ Date::now()->format('Y') }} Copyright
                <a href="{{ route('welcome') }}" class="text-gray-700 ml-1 hover:text-blue-500">Kuchas Kids</a>
            </p>
            <span class="hidden lg:inline-flex sm:ml-auto sm:mt-0 mt-2 justify-center sm:justify-start">
                <a href="https://www.facebook.com/KuchasKids" target="_blank" class="text-gray-500 hover:text-violet-150">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/kuchaskids/" target="_blank"
                    class="ml-3 text-gray-500 hover:text-violet-150">
                    <i class="fab fa-instagram"></i>
                </a>
            </span>
        </div>
    </div>
</footer>
