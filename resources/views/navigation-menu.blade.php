@php
  $menu = [
      [
          'route' => 'admin.index',
          'active' => 'admin.index',
          'name' => 'Productos',
      ],
      [
          'route' => 'admin.orders.index',
          'active' => 'admin.orders.*',
          'name' => 'Órdenes',
      ],
      [
          'route' => 'admin.banners.index',
          'active' => 'admin.banners.*',
          'name' => 'Banners',
      ],
      [
          'route' => 'admin.categories.index',
          'active' => 'admin.categories.*',
          'name' => 'Categorías',
      ],
      [
          'route' => 'admin.brands.index',
          'active' => 'admin.brands.*',
          'name' => 'Marcas',
      ],
      [
          'route' => 'admin.zones.index',
          'active' => 'admin.zones.*',
          'name' => 'Zonas',
      ],
      [
          'route' => 'admin.users.index',
          'active' => 'admin.users.*',
          'name' => 'Usuarios',
      ],
      [
          'route' => 'admin.settings.index',
          'active' => 'admin.settings.*',
          'name' => 'Configuración',
      ],
  ];
@endphp

<div class="flex flex-col mx-auto bg-white">
  <aside x-show="open"
    class="flex flex-col shrink-0 lg:w-[300px] w-[250px] transition-all duration-300 ease-in-out m-0 z-40 inset-y-0 left-0 bg-white border-r border-r-dashed border-r-neutral-200"
    :class="{ 'w-16': !open }" id="sidenav-main">
    <div class="flex items-center justify-between h-[96px] px-4">
      <img src="{{ Storage::url($settings_company->logo) }}" class="h-16 w-full" alt="logo">
    </div>

    <div class="border-b border-dashed lg:block dark:border-neutral-700/70 border-neutral-200"></div>

    <div class="relative pl-3 my-5 overflow-y-scroll">
      <div class="flex flex-col w-full font-medium">
        @foreach ($menu as $item)
          <div>
            <span class="select-none flex items-center px-4 py-[.775rem] cursor-pointer my-[.4rem] rounded-[.95rem]">
              <x-nav-link
                class="flex items-center flex-grow text-[1.15rem] dark:text-neutral-400/75 text-stone-500 hover:text-dark"
                href="{{ route($item['route']) }}" :active="request()->routeIs($item['active'])">
                {{ $item['name'] }}
              </x-nav-link>
            </span>
          </div>
        @endforeach
      </div>
    </div>
  </aside>
</div>
