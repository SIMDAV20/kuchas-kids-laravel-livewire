@php
    $menu = [
        [
            'group' => 'Catálogo',
            'icon'  => 'fa-box-open',
            'items' => [
                ['route' => 'admin.index',            'active' => 'admin.index|admin.products.*', 'name' => 'Productos'],
                ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'name' => 'Categorías'],
                ['route' => 'admin.brands.index',     'active' => 'admin.brands.*',     'name' => 'Marcas'],
                ['route' => 'admin.attributes.index', 'active' => 'admin.attributes.*', 'name' => 'Atributos'],
            ],
        ],
        [
            'group' => 'Ventas',
            'icon'  => 'fa-shopping-cart',
            'items' => [
                ['route' => 'admin.orders.index', 'active' => 'admin.orders.*', 'name' => 'Órdenes'],
                ['route' => 'admin.zones.index',  'active' => 'admin.zones.*',  'name' => 'Zonas'],
            ],
        ],
        [
            'group' => 'Contenido',
            'icon'  => 'fa-image',
            'items' => [
                ['route' => 'admin.banners.index', 'active' => 'admin.banners.*', 'name' => 'Banners'],
            ],
        ],
        [
            'group' => 'Administración',
            'icon'  => 'fa-cog',
            'items' => [
                ['route' => 'admin.users.index',    'active' => 'admin.users.*',    'name' => 'Usuarios'],
                ['route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'name' => 'Configuración'],
            ],
        ],
    ];
@endphp

<aside class="w-64 min-h-screen bg-gray-900 text-gray-100 flex flex-col flex-shrink-0">

    {{-- LOGO --}}
    <div class="flex items-center justify-center px-6 py-5 border-b border-gray-700">
        <a href="{{ route('admin.index') }}">
            <img src="{{ Storage::url($settings_company->logo) }}" class="h-10 w-auto" alt="logo">
        </a>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
        @foreach ($menu as $group)
            @php
                $groupActive = collect($group['items'])->contains(
                    fn($item) => request()->routeIs(explode('|', $item['active']))
                );
            @endphp

            <div x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }">

                {{-- GROUP HEADER --}}
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-colors
                        {{ $groupActive ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <span class="flex items-center gap-2">
                        <i class="fas {{ $group['icon'] }} w-4 text-center"></i>
                        {{ $group['group'] }}
                    </span>
                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                </button>

                {{-- GROUP ITEMS --}}
                <div x-show="open" x-collapse class="mt-1 space-y-0.5 pl-4">
                    @foreach ($group['items'] as $item)
                        @php $isActive = request()->routeIs(explode('|', $item['active'])); @endphp
                        <a href="{{ route($item['route']) }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
                                {{ $isActive
                                    ? 'bg-indigo-600 text-white font-semibold'
                                    : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 flex-shrink-0
                                {{ $isActive ? 'bg-white' : 'bg-gray-600' }}"></span>
                            {{ $item['name'] }}
                        </a>
                    @endforeach
                </div>

            </div>
        @endforeach
    </nav>

    {{-- VER TIENDA --}}
    <div class="px-3 pb-2">
        <a href="{{ route('welcome') }}"
            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
            <i class="fas fa-store w-4 text-center"></i>
            Ver tienda
        </a>
    </div>

    {{-- USER --}}
    <div class="border-t border-gray-700 px-4 py-4">
        <div class="flex items-center gap-3 mb-3">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <img class="h-8 w-8 rounded-full object-cover flex-shrink-0"
                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
            @else
                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('profile.show') }}"
                class="flex-1 text-center text-xs text-gray-400 hover:text-white py-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                <i class="fas fa-user-circle mr-1"></i> Perfil
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full text-xs text-gray-400 hover:text-red-400 py-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-sign-out-alt mr-1"></i> Salir
                </button>
            </form>
        </div>
    </div>

</aside>
