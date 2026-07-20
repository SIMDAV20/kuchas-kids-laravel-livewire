# Flujo de Banners (Banner Workflow)

Este documento describe la gestión de banners del storefront: subida, validación (incluida la
validación de dimensiones para que el banner se vea correctamente) y eliminación.

## 1. Componente

- **Clase:** `App\Http\Livewire\Admin\UploadBanner` (`app/Http/Livewire/Admin/UploadBanner.php`)
- **Vista:** `resources/views/livewire/admin/upload-banner.blade.php`
- **Modelo:** `App\Models\Banner` (columna `photo` = ruta relativa en el disco `public`)
- **Ruta:** admin (gated por `role:admin,web`), layout `layouts.admin`

La subida usa el componente compartido `<x-file-attachment>` en modo `profile`, enlazado a
`wire:model="image"`. La validación NO vive en ese componente compartido, vive en el `$rules` de
`UploadBanner`, así que ajustarla no afecta a los otros usos de `<x-file-attachment>` (logo, perfil,
productos).

## 2. Subida y Validación (`uploadBanner`)

1. Se asigna el archivo temporal: `$this->photo = $this->image;`.
2. `validateOnly('photo')` aplica las reglas.
3. **Defensa anti-500:** si el archivo temporal llegó roto (ruta vacía / inválido) o `Storage::put`
   lanza excepción, se agrega un error amable con `addError('photo', …)` y se corta — evita el
   `ValueError: Path cannot be empty`.
4. Se guarda con `Storage::put('banners', $this->photo)` y se crea el `Banner`.
5. Se emite `upload_banner`, se resetea el input y se recarga la lista.

### Reglas (`$rules`)

```php
'photo' => 'required|image|mimes:png,jpg,jpeg|max:5120|dimensions:min_width=731,min_height=316,ratio=731/316'
```

- `required` — obligatorio.
- `image` + `mimes:png,jpg,jpeg` — solo imágenes JPG/PNG.
- `max:5120` — máximo 5 MB.
- **`dimensions:min_width=731,min_height=316,ratio=731/316`** — el banner recomendado es **731×316 px**.
  - `min_width` / `min_height`: mínimo 731×316 (evita imágenes pequeñas/borrosas).
  - `ratio=731/316`: obliga la proporción exacta 731:316 (≈ 2.31:1) para que el banner **no se
    deforme ni se recorte** en el slot del storefront.
  - Permite imágenes más grandes siempre que respeten la proporción (ej. 1462×632 ✅; 731×320 ❌).

### Mensajes en español (`$messages`)

Cada regla tiene su texto amigable; el de dimensiones:

```php
'photo.dimensions' => 'La imagen debe medir mínimo 731×316 px y mantener la proporción 731:316.'
```

Los errores se muestran en la vista con `@error('photo')` debajo del recuadro de subida.

> Si la proporción exacta resulta muy estricta en la práctica, se puede aflojar quitando `ratio` y
> dejando solo `min_width`/`min_height`.

## 3. Eliminación (`delete`)

- Se dispara desde la vista con SweetAlert (`$emit('deleteBanner', id)` → confirma → `emitTo` `delete`).
- Borra el archivo del disco si existe (`Storage::delete`) y elimina el registro.

## 4. Notas de entorno

La subida de archivos depende de que PHP tenga un directorio temporal escribible. En PHP 8.3 sobre
Laragon (FastCGI) esto requirió configuración; ver `docs/upload-fix-php83.md`.
