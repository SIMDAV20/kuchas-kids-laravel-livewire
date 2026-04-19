# Product View — Swiper & Slider Dependencies

## Overview

`resources/views/livewire/product-view.blade.php` — rendered by `app/Http/Livewire/ProductView.php`.

Uses **three** slider libraries, each serving a different context:

| Library | Context | Load method |
|---------|---------|-------------|
| Swiper 11 | Desktop image gallery (main + thumbs) | CDN |
| FlexSlider | Mobile image gallery | Local vendor |
| Glider.js | Related products carousel | Local vendor |

---

## Swiper 11

### Dependencies

Both loaded in `resources/views/layouts/app.blade.php` — global, not lazy.

```html
<!-- CSS (line 85) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- JS (line 113) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
```

> jQuery is also required on the page (`jquery-3.6.0.min.js`) because Swiper init is wrapped in `$(document).ready()`.

### DOM Structure

Desktop only (`hidden md:block`). Two swiper instances — a main viewer and a thumbnails strip.

```html
<!-- Main viewer -->
<div style="--swiper-navigation-color: #BCBBE3;" class="swiper galleryTopSwipper">
  <div class="swiper-wrapper">
    <li class="swiper-slide"><img src="..."></li>
    ...
  </div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>

<!-- Thumbnails strip -->
<div thumbsSlider class="galleryThumbsSwipper">
  <div class="swiper-wrapper">
    <li class="swiper-slide"><img src="..."></li>
    ...
  </div>
</div>
```

Both wrappers sit inside `wire:init="loadGallery"` — Livewire triggers gallery init on component mount.

### JavaScript Initialization

Located in `@push('scripts')` at the bottom of the blade file.

```js
$(document).ready(function() {
  Livewire.on('swiperRefresh', function() {

    const galleryThumbs = new Swiper(".galleryThumbsSwipper", {
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });

    const galleryTop = new Swiper(".galleryTopSwipper", {
      direction: 'horizontal',
      loop: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      thumbs: {
        swiper: galleryThumbs,   // links thumbnail strip to main viewer
      },
    });

  });
});
```

### Livewire Integration

Swiper is **not** initialized on page load — it waits for the `swiperRefresh` event emitted by Livewire.

| Trigger | Method | When fired |
|---------|--------|-----------|
| `wire:init="loadGallery"` | `loadGallery()` | Component first renders |
| `handleSelectColor()` | `$this->emit('swiperRefresh')` | User picks a color variant |
| `handleSelectSize()` | `$this->emit('swiperRefresh')` | User picks a size variant |

This pattern re-instantiates Swiper each time the image set changes after a variant selection.

### Swiper Config Notes

- Navigation arrows use CSS custom property `--swiper-navigation-color: #BCBBE3` (matches `violet-350`).
- `galleryThumbsSwipper` uses `watchSlidesProgress: true` — required for the thumb-highlight to work.
- `galleryTopSwipper` uses `loop: true` — slides wrap around.
- No autoplay configured.

---

## FlexSlider (Mobile Gallery)

Only visible on mobile (`sm:block md:hidden`). Uses local vendor files.

```html
<!-- CSS -->
<link rel="stylesheet" href="{{ asset('vendor/FlexSlider/flexslider.css') }}">

<!-- JS (requires jQuery) -->
<script src="{{ asset('vendor/FlexSlider/jquery.flexslider-min.js') }}"></script>
```

DOM uses `data-thumb` for thumbnail hints:

```html
<div class="flexslider sm:block md:hidden">
  <ul class="slides">
    <li data-thumb="{{ Storage::url($image) }}">
      <img src="{{ Storage::url($image) }}" />
    </li>
  </ul>
</div>
```

> No explicit JS init found in this blade file — FlexSlider likely auto-initializes or is initialized elsewhere.

---

## Glider.js (Related Products)

Used for the related products carousel. Local vendor files.

```html
<!-- CSS -->
<link rel="stylesheet" href="{{ asset('vendor/glider/css/glider.css') }}">

<!-- JS -->
<script src="{{ asset('vendor/glider/js/glider.min.js') }}"></script>
```

Initialized via Livewire event `glider`:

```js
Livewire.on('glider', function(id) {
  Alpine.start();
  new Glider(document.querySelector('.glider-' + id), {
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: '.glider-' + id + '~.dots',
    arrows: {
      prev: '.glider-' + id + '~.glider-prev',
      next: '.glider-' + id + '~.glider-next',
    },
    responsive: [
      { breakpoint: 640,  settings: { slidesToShow: 2.5, slidesToScroll: 2 } },
      { breakpoint: 768,  settings: { slidesToShow: 3.5, slidesToScroll: 3 } },
      { breakpoint: 1024, settings: { slidesToShow: 4.5, slidesToScroll: 4 } },
      { breakpoint: 1280, settings: { slidesToShow: 5.5, slidesToScroll: 5 } },
    ]
  });
});
```

Uses `~` CSS sibling selector for dots and arrow targets relative to the glider container.

> The `@livewire('category-products')` embed that would fire this event is currently commented out.

---

## All Dependencies Summary

| Asset | Version | Source | Purpose |
|-------|---------|--------|---------|
| `swiper-bundle.min.css` | 11 | jsDelivr CDN | Swiper styles |
| `swiper-bundle.min.js` | 11 | jsDelivr CDN | Swiper core |
| `flexslider.css` | — | `public/vendor/FlexSlider/` | FlexSlider styles |
| `jquery.flexslider-min.js` | — | `public/vendor/FlexSlider/` | FlexSlider core |
| `glider.css` | — | `public/vendor/glider/css/` | Glider styles |
| `glider.min.js` | — | `public/vendor/glider/js/` | Glider core |
| `jquery-3.6.0.min.js` | 3.6.0 | jQuery CDN | Required by Swiper init wrapper & FlexSlider |
| `fontawesome-free` | — | `public/vendor/fontawesome-free/` | Truck icon in delivery info |

All assets loaded globally in `resources/views/layouts/app.blade.php`.
