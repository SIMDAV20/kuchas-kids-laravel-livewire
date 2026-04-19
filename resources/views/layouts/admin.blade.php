<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('ico/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('ico/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('ico/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('ico/site.webmanifest') }}">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

  <!-- Styles -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Fontawesome --}}
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

  {{-- DropZone --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css"
    integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- Image Viewer --}}
  <link rel="stylesheet" href="{{ asset('vendor/images-viewer/css/master.css') }}">

  {{-- Tooltip https://atomiks.github.io/tippyjs/v6/getting-started/ --}}
  <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css" />

  {{-- Pickaday https://github.com/Pikaday/Pikaday --}}
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

  @livewireStyles

  <!-- Scripts -->

  {{-- Jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  {{-- Skeditor --}}
  <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>

  {{-- SweetAlert2 --}}
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- SortableJS --}}
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

  {{-- DropZone --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"
    integrity="sha512-VQQXLthlZQO00P+uEu4mJ4G4OAgqTtKG1hri56kQY1DtdLeIqhKUp9W/lllDDu3uN3SnUNawpW7lBda8+dSi7w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  {{-- Image Viewer --}}
  <script src="{{ asset('vendor/images-viewer/js/main.js') }}"></script>

  {{-- Tooltip https://atomiks.github.io/tippyjs/v6/getting-started/ --}}
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>

  {{-- Pickaday https://github.com/Pikaday/Pikaday --}}
  <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

</head>

<body class="font-sans antialiased">
  <x-banner />

  <div class="min-h-screen bg-gray-100 flex">
    @include('navigation-admin-menu')

    <!-- Page Content -->
    <main class="flex-1 min-w-0 overflow-x-hidden">
      {{ $slot }}
    </main>
  </div>

  @stack('modals')

  @livewireScripts

  <script>
    Livewire.on('errorSize', mensaje => {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: mensaje,
      })
    })

    Livewire.on('deleteImageProduct', imageId => {
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

          Livewire.emitTo('admin.gallery-images-products', 'delete', imageId);

          Swal.fire(
            'Eliminado!',
            'El resgistro ha sido eliminado.',
            'success'
          )
        }
      })
    })
  </script>

  {{-- Livewire Sortable --}}
  <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>

  @stack('scripts')
</body>

</html>
