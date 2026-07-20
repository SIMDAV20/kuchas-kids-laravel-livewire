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
                ['route' => 'admin.coupons.index', 'active' => 'admin.coupons.*', 'name' => 'Cupones'],
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

{{-- Mobile backdrop --}}
<div x-show="mobileOpen" x-cloak x-transition.opacity @click="closeMobile()"
    class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col flex-shrink-0 w-72 bg-gray-900 text-gray-100 transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen"
    :class="{ 'translate-x-0': mobileOpen, 'lg:w-64': !collapsed, 'lg:w-[76px]': collapsed }">

    {{-- LOGO + TOGGLES --}}
    <div class="flex items-center h-16 flex-shrink-0 border-b border-gray-700 px-4"
        :class="collapsed ? 'lg:justify-center lg:px-0' : 'justify-between'">
        <a href="{{ route('admin.index') }}" class="min-w-0" :class="{ 'lg:hidden': collapsed }">
            <img src="{{ Storage::url($settings_company->logo) }}" class="h-9 w-auto max-w-[10rem] object-contain"
                alt="logo">
        </a>

        {{-- Collapse toggle (desktop only) --}}
        <button @click="toggleCollapsed()" :title="collapsed ? 'Expandir menú' : 'Colapsar menú'"
            class="hidden lg:flex items-center justify-center h-8 w-8 rounded-lg bg-gray-800 text-gray-300 ring-1 ring-gray-700 hover:text-white hover:bg-gray-700 transition-colors flex-shrink-0">
            <i class="fas fa-angle-double-left text-sm transition-transform duration-300" :class="{ 'rotate-180': collapsed }"></i>
        </button>

        {{-- Close drawer (mobile only) --}}
        <button @click="closeMobile()" aria-label="Cerrar menú"
            class="lg:hidden h-9 w-9 flex items-center justify-center text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
        @foreach ($menu as $group)
            @php
                $groupActive = collect($group['items'])->contains(
                    fn($item) => request()->routeIs(explode('|', $item['active']))
                );
            @endphp

            <div class="relative" x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }">

                {{-- GROUP HEADER --}}
                <button @click="collapsed && !mobileOpen ? (toggleCollapsed(), open = true) : (open = !open)"
                    :title="collapsed ? '{{ $group['group'] }}' : ''"
                    class="w-full flex items-center rounded-lg text-xs font-black uppercase tracking-widest transition-colors
                        {{ $groupActive ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}"
                    :class="collapsed ? 'lg:justify-center lg:px-0 lg:py-3' : 'justify-between px-3 py-2'">
                    <span class="flex items-center gap-2" :class="{ 'lg:gap-0': collapsed }">
                        <i class="fas {{ $group['icon'] }} w-4 text-center"></i>
                        <span :class="{ 'lg:hidden': collapsed }">{{ $group['group'] }}</span>
                    </span>
                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                        :class="{ 'rotate-180': open, 'lg:!hidden': collapsed }"></i>
                </button>

                {{-- GROUP ITEMS --}}
                <div x-show="open && !(collapsed && !mobileOpen)" x-collapse class="mt-1 space-y-0.5 pl-4">
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
    <div class="px-3 pb-2 flex-shrink-0">
        <a href="{{ route('welcome') }}" title="Ver tienda"
            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-gray-800 transition-colors"
            :class="collapsed ? 'lg:justify-center lg:px-0' : ''">
            <i class="fas fa-store w-4 text-center flex-shrink-0"></i>
            <span :class="{ 'lg:hidden': collapsed }">Ver tienda</span>
        </a>
    </div>

    {{-- USER --}}
    <div class="border-t border-gray-700 px-4 py-4 flex-shrink-0">
        <div class="flex items-center gap-3 mb-3" :class="{ 'lg:justify-center lg:gap-0': collapsed }">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <img class="h-8 w-8 rounded-full object-cover flex-shrink-0"
                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
            @else
                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0" :class="{ 'lg:hidden': collapsed }">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="flex gap-2" :class="{ 'lg:flex-col': collapsed }">
            <a href="{{ route('profile.show') }}" title="Perfil"
                class="flex-1 text-center text-xs text-gray-400 hover:text-white py-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                <i class="fas fa-user-circle mr-1"></i>
                <span :class="{ 'lg:hidden': collapsed }">Perfil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" title="Salir"
                    class="w-full text-xs text-gray-400 hover:text-red-400 py-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    <span :class="{ 'lg:hidden': collapsed }">Salir</span>
                </button>
            </form>
        </div>
    </div>

</aside>
