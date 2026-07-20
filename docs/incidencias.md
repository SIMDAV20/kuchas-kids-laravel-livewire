# Incidencias resueltas

Bugs reales encontrados en la auditoría técnica (`docs/ecommerce-technical-processes.md`, sección 6.2)
y corregidos en esta sesión. Un archivo por fila; cada uno se verificó manualmente (navegador y/o
Tinker) después del fix.

| Archivo | Qué pasó y cómo se solucionó |
|---|---|
| `app/Http/Controllers/OrderController.php` | `izipay()` podía tirar error fatal con un `order_id` inválido/ausente (dereferenciaba `null`); se agregó guard temprano. También: `pay()` tenía un token de MercadoPago hardcodeado (movido a `config('services.mercadopago.token')`), `annuled()` no autorizaba al dueño de la orden, y `orderFailure()` rompía por falta de `$categories` (ahora redirige en vez de renderizar la vista directo). |
| `.env` | Agregada `MP_ACCESS_TOKEN` (antes vivía hardcodeada en el código fuente de `OrderController::pay()`). |
| `routes/web.php` | La ruta `POST webhooks` apuntaba a `WebhooksController` con una acción de array inválida (sin método), y la clase está 100% comentada — ruta no funcional. Se eliminó la ruta y el `use` huérfano. |
| `app/Http/Livewire/Admin/StatusOrder.php` | La condición `$status > 1 && $status > 5` nunca era verdadera — marcar una orden RECIBIDO/ENVIADO/ENTREGADO nunca aprobaba el pago; corregida a `< 5`. Anular una orden desde el admin tampoco restauraba stock ni usaba transacción (a diferencia del flujo del cliente); ahora hace ambas cosas. |
| `app/Models/Order.php` | `department()/city()/district()` referenciaban columnas que no existen en `orders` (relaciones muertas, eliminadas). `scopeOtherContact/OtherPhone/OtherDocnumber` consultaban las columnas sin "Other" por copy-paste; corregidas a las columnas reales. |
| `app/Http/Livewire/Admin/ShowOrders.php` | El filtro de status se agregaba después de una cadena de `orWhere` sin agrupar, así que buscar + filtrar por status a la vez podía devolver órdenes de otro status (precedencia SQL). Se agrupó la búsqueda en un closure `where()`. |
| `app/Http/Controllers/Admin/ProductController.php` | `files()` llamaba `$product->images()`, un método que no existe (`Product` solo define `images_morph()`) — quedaría fatal si se invocaba. Corregido a `images_morph()`. |
| `app/Http/Livewire/Admin/EditProduct.php` | No existía forma de editar el stock (`quantity`) de un producto base después de crearlo. Se agregó la regla de validación del campo. |
| `resources/views/livewire/admin/edit-product.blade.php` | Complemento del fix anterior: se agregó el input "Stock Base" junto a precio/oferta. |
| `app/Http/Livewire/Admin/BrandComponent.php` | La validación de unicidad al editar una marca usaba `$this->brand->name`/`->slug` como "except" en vez del `id` — podía rechazar guardar una marca sin cambios como duplicada de sí misma. |
| `app/Models/Subcategory.php` | `keywords` es una columna JSON sin `$casts`, forzando a cada consumidor a hacer `json_encode`/`json_decode` manual. Se agregó `protected $casts = ['keywords' => 'array']`. |
| `app/Http/Livewire/Admin/ShowCategory.php` | Ajustado tras el cast: ya no hace `json_encode()` al guardar ni `json_decode()` al cargar el form de edición (el modelo lo resuelve solo). |
| `app/Http/Controllers/WelcomeController.php` | Ajustado tras el cast en `Subcategory`: quitado el `json_decode()` redundante sobre `$subcategory->keywords` (ya llega como array), que ahora habría fallado con `TypeError`. |
| `app/Http/Controllers/ProductController.php` | Mismo ajuste que arriba, en el bloque de SEO de la vista de detalle de producto. |
| `app/Http/Controllers/SearchController.php` | Mismo ajuste que arriba, en el bloque de SEO de resultados de búsqueda. |
| `app/Http/Livewire/CategoryFilter.php` | Mismo ajuste de `keywords`, más: la URL canónica de SEO incluía los query params de filtro/paginación actuales (antipatrón de contenido duplicado) — ahora usa la URL base sin params. Se quitó el import `Arr` que quedó sin uso. |
| `database/migrations/2022_12_27_184525_create_banners_table.php` | El guard de creación verificaba `Schema::hasTable('settings')` en vez de `'banners'` (copy-paste) — en una migración desde cero, la tabla `banners` nunca se habría creado. Corregido el nombre de tabla. |
| `database/migrations/2021_08_12_173306_create_districts_table.php` | El `Schema::create` estaba completamente comentado — la tabla `districts` real no la creó esta migración. Se reconstruyó el `up()` a partir del esquema real de producción (introspección vía `SHOW CREATE TABLE`), para que una instalación nueva sí la genere correctamente. |
| `app/Models/Province.php` | `$fillable` tenía el typo `deparment_id` en vez de `department_id` (la columna real está bien escrita). Corregido. |
| `app/Http/Controllers/CategoryController.php` | `show()` no verificaba que la categoría estuviera pública — una categoría oculta seguía siendo visible si se conocía el slug. Se agregó `abort_unless($category->status == Category::PUBLIC, 404)`. |

## No corregidos (quedaron fuera a propósito)

Del resto de hallazgos en la sección 6.2 del doc técnico, estos **no se tocaron** por ser trabajo de
feature nueva o refactors grandes de arquitectura, no bugs acotados:

- Verificación de firma/HMAC del callback de Izipay (hardening de seguridad).
- UI de creación/programación de ofertas flash (no existe hoy, es una feature completa).
- Normalización de los tres vocabularios de estado (Producto/Categoría/Variante) — requiere migración de datos.
- Paginación de `CategoryFilter` en PHP en vez de SQL (mejora de performance, no está roto hoy).
- Duplicación de la lógica de redirect post-login admin/cliente (no está roto, solo repetido).
- Protección anti-spam y envío en cola para `ContactForm`/`ComplaintsBook` (hardening, no bug).
