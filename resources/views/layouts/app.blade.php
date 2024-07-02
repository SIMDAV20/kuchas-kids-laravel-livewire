<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('ico/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('ico/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('ico/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('ico/site.webmanifest') }}">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  {!! SEO::generate() !!}

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


  <!-- Meta Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '2522462224630367');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=2522462224630367&ev=PageView&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-DMPNZD39NW"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-DMPNZD39NW');
  </script>

  @stack('izipay')

  <!-- Styles -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}

  {{-- Fontawesome --}}
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

  {{-- Glider --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.7/glider.min.css"
    integrity="sha512-YM6sLXVMZqkCspZoZeIPGXrhD9wxlxEF7MzniuvegURqrTGV2xTfqq1v9FJnczH+5OGFl5V78RgHZGaK34ylVg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- FlexSlider --}}
  <link rel="stylesheet" href="{{ asset('vendor/FlexSlider/flexslider.css') }}">

  {{-- Image Viewer --}}
  <link rel="stylesheet" href="{{ asset('vendor/images-viewer/css/master.css') }}">

  @livewireStyles

  <!-- Scripts -->
  {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}

  {{-- Glider --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.7/glider.min.js"
    integrity="sha512-tHimK/KZS+o34ZpPNOvb/bTHZb6ocWFXCtdGqAlWYUcz+BGHbNbHMKvEHUyFxgJhQcEO87yg5YqaJvyQgAEEtA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  {{-- SweetAlert2 --}}
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  {{-- FlexSlider --}}
  <script src="{{ asset('vendor/FlexSlider/jquery.flexslider-min.js') }}"></script>

  {{-- Image Viewer --}}
  <script src="{{ asset('vendor/images-viewer/js/main.js') }}"></script>

  {{-- Bxslider --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

</head>

<body class="font-sans antialiased">
  <x-banner />

  <div class="min-h-screen bg-gray-100">
    {{-- Menu publico --}}
    @livewire('navigation')

    <!-- Las demas paginas -->
    <main>
      {{ $slot }}
    </main>

    {{-- Pie de pagina publico --}}
    @livewire('footer-public')

  </div>

  @stack('modals')

  @livewireScripts

  <script>
    function dropdown() {
      return {
        open: false,
        show() {
          if (this.open) {
            // se cierra el menu
            this.open = false;
            document.getElementsByTagName('html')[0].style.overflow = 'auto'
          } else {
            // se esta abriendo el menu
            this.open = true;
            document.getElementsByTagName('html')[0].style.overflow = 'hidden'
          }
        },
        // para devolver el scroll
        close() {
          this.open = false;
          document.getElementsByTagName('html')[0].style.overflow = 'auto'
        }
      }
    }
  </script>

  {{-- Modernizer --}}
  <script src="{{ asset('vendor/plugins/modernizr-custom.js') }}"></script>

  @stack('scripts')

</body>

</html>
