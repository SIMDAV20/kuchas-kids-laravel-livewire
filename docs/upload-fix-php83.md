# Fix: Subida de imágenes falla en PHP 8.3 — `ValueError: Path cannot be empty`

Registro del diagnóstico y los cambios de entorno aplicados para arreglar la subida de
imágenes (banners y cualquier componente Livewire con `WithFileUploads`).

## Síntoma

Al subir un banner fallaba con, en orden:

1. El mensaje de validación no se mostraba (bug `@error($photo)` en la vista).
2. `ValueError: Path cannot be empty` desde el endpoint `/livewire/upload-file`.
3. `"El campo image no se pudo subir"` (regla de validación `uploaded`).

Los puntos 2 y 3 son la **misma falla**: el archivo subido llega a PHP con la **ruta temporal
vacía**, y Livewire hace `storeAs('/livewire-tmp', …)` → `fopen('', 'r')`.

## Causa raíz

Comprobado: **funciona en PHP 8.1, falla en PHP 8.3.** La diferencia NO es el `php.ini`
(son idénticos en lo relativo a uploads/temp), sino el **handler de Apache** que obliga cada build:

- `php-8.3.4-nts` es build **Non-Thread-Safe** → no tiene `php8apache2_4.dll` → no puede usar
  mod_php → Apache lo corre por **FastCGI** (`httpd.conf` → `Include etc/apache2/fcgid.conf`, `php-cgi.exe`).
- `fcgid.conf` fijaba `FcgidInitialEnv TEMP/TMP "C:/Windows/Temp"` y `upload_tmp_dir` estaba sin
  definir en `php.ini` → PHP escribía las subidas en `C:\Windows\Temp`.
- `C:\Windows\Temp` **no es escribible** por PHP (`is_writable = false`) → no guarda el archivo →
  `$_FILES[...]['tmp_name']` vacío → `getRealPath() === ''` → `fopen('', 'r')` → el `ValueError`.
- `php-8.1.10` es **Thread-Safe** → usa **mod_php** → temp escribible → por eso ahí sí funcionaba.

En 8.3 (FastCGI) el problema era: **PHP no tenía un directorio temporal escribible para las subidas.**

## Solución aplicada (se mantiene PHP 8.3)

Se le dio a PHP un directorio temporal escribible. Cambios **fuera del repo** (config de Laragon):

1. Se creó la carpeta `C:\laragon\tmp` (escribible).

2. `C:\laragon\bin\php\php-8.3.4-nts-Win32-vs16-x64\php.ini`:
   ```ini
   upload_tmp_dir = "C:/laragon/tmp"
   sys_temp_dir  = "C:/laragon/tmp"
   ```
   (antes ambos comentados: `;upload_tmp_dir =` / `;sys_temp_dir = "/tmp"`)

3. `C:\laragon\etc\apache2\fcgid.conf`:
   ```
   FcgidInitialEnv TEMP "C:/laragon/tmp"
   FcgidInitialEnv TMP  "C:/laragon/tmp"
   ```
   (antes apuntaban a `C:/Windows/Temp`)

4. **Reiniciar Apache** desde Laragon (Menú → Apache → Reload). Los cambios de `php.ini`/`fcgid.conf`
   no aplican hasta reiniciar.

> Nota: estos cambios son de la máquina local (Laragon), no del repositorio. Si se reinstala o
> se cambia la versión de PHP en Laragon, hay que volver a aplicarlos (o usar PHP 8.1, que ya
> funciona con mod_php).

## Cambios en el repo (esta sesión)

- `resources/views/livewire/admin/upload-banner.blade.php`: `@error($photo)` → `@error('photo')`
  (así se muestra el mensaje de validación).
- `app/Http/Livewire/Admin/UploadBanner.php`: se agregó `$messages` con textos en español por regla
  (`required`, `image`, `mimes`, `max`) para mejor UX.

## Verificación

- `http://kuchas-kids.test/_uploadcheck.php` → debe mostrar `writable: true` y
  `upload_tmp_dir: C:/laragon/tmp` (SAPI `cgi-fcgi`). Borrar ese archivo tras verificar.
- Subir un JPG/PNG en el admin de banners → previsualiza, "Imagen Agregada", aparece en la lista
  y en `storage/app/public/banners`. Sin nuevos `ValueError` en `storage/logs/laravel.log`.

## Nota sobre archivos grandes

`fcgid.conf` limita el cuerpo de la petición a `FcgidMaxRequestLen 81310720` (~77 MB). Suficiente
para el límite de 5 MB del banner; subir esto solo si en el futuro se necesitan subidas más grandes.
