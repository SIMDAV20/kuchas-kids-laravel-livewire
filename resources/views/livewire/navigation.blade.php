<header class="bg-white sticky top-0 shadow-xl" style="z-index: 10000" x-data="dropdown()">
    @if ($settings_company->min_amount > 0)
        <div class="py-2 px-1 text-center bg-violet-150 text-gray-550">
            <p class="font-semibold md:text-base text-xs">
                {{ $settings_company->headband_one }} S/{{ $settings_company->min_amount }}
                {{ $settings_company->headband_two }}
            </p>
        </div>
    @endif

    <div class="container flex items-center h-16 justify-between md:justify-start">
        {{-- boton de las categorias con el icon --}}
        <a :class="{ 'text-violet-350': open }" x-on:click="show()"
            class="flex flex-col items-center justify-center px-2 md:px-4 bg-white text-gray-350 cursor-pointer font-semibold h-full">
            <i class="fas fa-bars" style="font-size: 24px"></i>
            <span class="hidden md:block">Categorías</span>
        </a>
        <a href="{{ route('welcome') }}" class="mx-6 max-w-sm">
            {{-- LOGO --}}
            <img src="{{ asset('img/logo.jpg') }}" class="h-16" style="width: 140px" alt="">

        </a>

        <div class="flex-1 hidden md:block">
            @livewire('search')
        </div>
        <!-- Usuario autenticado o no -->
        <div class="mx-6 relative hidden md:block">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="{{ route('orders.index') }}">
                            Mis órdenes
                        </x-dropdown-link>

                        @role('admin')
                            <x-dropdown-link href="{{ route('admin.index') }}">
                                Administrador
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>
                        @endrole
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        {{-- USUARIO --}}
                        <i class="fas fa-user-circle text-gray-350 text-3xl cursor-pointer"></i>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('login') }}">
                            {{ __('Login') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="{{ route('register') }}">
                            {{ __('Register') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            @endauth
        </div>

        <div class="px-2 pt-1">
            @livewire('dropdown-cart')
        </div>
    </div>

    {{-- class dinamico si open es true o false --}}
    <nav id="navigation-menu" :class="{ 'block': open, 'hidden': !open }" x-show="open"
        class="bg-trueGray-700 w-full bg-opacity-25 absolute hidden">
        {{-- Menu desktop --}}
        <div class="container h-full hidden md:block">
            <div x-on:click.away="close()" class="grid grid-cols-4 h-full relative">
                <ul class="bg-white">
                    @foreach ($categories as $category)
                        <li class="navigation-link font-bold">
                            <a href="{{ route('categories.show', $category) }}"
                                class="py-2 px-4 text-sm flex items-center text-gray-550 hover:bg-violet-150 hover:text-violet-350">
                                <span class="flex justify-center w-9">
                                    {!! $category->icon !!}
                                </span>
                                {{ $category->name }}
                            </a>
                            <div class="navigation-submenu bg-gray-100 absolute w-3/4 h-full top-0 right-0 hidden">
                                <x-navigation-subcategories :category="$category" />
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="col-span-3 bg-gray-100">
                    {{-- pasar por prop con : --}}
                    <x-navigation-subcategories :category="$categories->first()" />
                </div>
            </div>
        </div>

        {{-- Menu Mobil --}}
        <div class="bg-white h-full overflow-y-auto">
            <div class="container bg-gray-200 py-2 mb-2">
                @livewire('search')
            </div>
            <p class="title-font font-semibold text-blue-800 text-trueGaray-500 px-4 my-2 uppercase">Categorías</p>
            <ul>
                @foreach ($categories as $category)
                    <li class="hover:bg-violet-150 hover:text-violet-350">
                        <a href="{{ route('categories.show', $category) }}"
                            class="py-2 px-4 text-sm flex items-center">
                            {{-- <span class="flex justify-center w-9">
                                {!! $category->icon !!}
                            </span> --}}
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <hr class="my-2">

            {{-- @livewire('cart-mobil') --}}

            @auth
                <a href="{{ route('profile.show') }}"
                    class="py-2 px-4 text-sm flex items-center hover:bg-violet-150 hover:text-violet-350">
                    <span class="flex justify-center w-9">
                        <i class="far fa-address-card"></i>
                    </span>
                    Perfil
                </a>

                {{-- llama al form para salir --}}
                <a href=""
                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit()"
                    class="py-2 px-4 text-sm flex items-center hover:bg-violet-150 hover:text-violet-350">
                    <span class="flex justify-center w-9">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>
                    Cerrar sesión
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="py-2 px-4 text-sm flex items-center hover:bg-violet-150 hover:text-violet-350">
                    <span class="flex justify-center w-9">
                        <i class="fas fa-user-circle"></i>
                    </span>
                    Iniciar sesión
                </a>

                <a href="{{ route('register') }}"
                    class="py-2 px-4 text-sm flex items-center hover:bg-violet-150 hover:text-violet-350">
                    <span class="flex justify-center w-9">
                        <i class="fas fa-fingerprint"></i>
                    </span>
                    Registrarse
                </a>
            @endauth
        </div>
    </nav>
</header>
