# Kuchas Kids — Documentación Técnica de Procesos (E-commerce)

> **Propósito:** mapear, a nivel técnico, todos los procesos del sistema — panel admin y storefront —
> como referencia para evaluar/planificar una reescritura del frontend (Next.js o Nuxt.js) manteniendo
> Laravel como API. Complementa (no reemplaza) los docs existentes en `docs/`: `flow.md`,
> `cart-workflow.md`, `product-workflow.md`, `banner-workflow.md`, `product-view.md`,
> `upload-fix-php83.md`. Donde ya existe documentación detallada de un módulo, este documento la
> resume y enlaza en vez de duplicarla, y añade notas de cambios posteriores a esos docs.

---

## 1. Arquitectura general

- **Stack:** Laravel `^10.0` tratado con sintaxis/convenciones Laravel 9 (`Route::`, `$this->middleware()`),
  Livewire `^2.11` (sin atributos PHP, sin `wire:navigate`, sin Volt), Alpine.js, Tailwind CSS,
  MySQL/MariaDB, PHP 8.3 (Apache vía `mod_fcgid`, ver `docs/upload-fix-php83.md` para el detalle del
  entorno de subida de archivos).
- **Enrutamiento:** `app/Providers/RouteServiceProvider.php` sobreescribe `map()` para cargar **tres**
  archivos de rutas por separado (no es el wiring por defecto de Laravel):
  - `routes/web.php` — storefront público, middleware `web`.
  - `routes/admin.php` — todo prefijado `admin/`, middleware `['web', 'auth', 'role:admin,web']`
    (gate por rol vía Spatie `laravel-permission`). Las rutas admin **no** necesitan re-agregar el
    prefijo ni el middleware de auth manualmente.
  - `routes/api.php` — prefijo `api/`, middleware `api`.
- **Auth:** Laravel Jetstream + Fortify (registro, 2FA, sesiones, tokens API — scaffolding estándar,
  ver sección 3.9). Autorización de admin vía Spatie `laravel-permission`, rol `admin` verificado por
  el middleware `role:admin,web` — no hay un flag `is_admin` custom.
- **Carrito:** `hardevine/shoppingcart` (`Gloudemans\Shoppingcart\Facades\Cart`), basado en sesión (no
  tabla propia). El stock real vive en `products.quantity` o, si el producto tiene variantes, en
  `product_variants.stock` (`Product::getStockAttribute()` suma el stock de variantes).
- **Pagos:** Izipay/PayZen (`lyracom/rest-php-sdk`) vía formulario embebido (Krypton) + Yape con
  comprobante manual subido por el cliente. Ver sección 3.7.
- **Localización:** `laraveles/spanish` (Carbon/validación en español) — la app es 100% en español.
- **SEO:** `artesaos/seotools`, orquestado por el helper global `setSEOTools()` (`app/helpers.php`).

---

## 2. Panel Admin

Estructura de navegación real (`resources/views/navigation-admin-menu.blade.php`), 4 grupos:

### 2.1 Catálogo

#### Productos
Documentado en profundidad en **[docs/product-workflow.md](product-workflow.md)** (ciclo de vida
completo: atributos globales → creación base → generación cartesiana de variantes → edición en línea
→ galería de imágenes) y **[docs/flow.md](flow.md)** (esquema ER de `products`/`product_variants`/
`attributes`/`attribute_options`/`flash_offers` + diagrama de flujo de frontend).

**Rutas:** `GET admin.index` (`ShowProducts`, listado), `GET admin.products.create` (`CreateProduct`),
`GET admin.products.edit` (`EditProduct`, bind por slug), `POST admin.products.files`
(`ProductController::files` — **ver bug #10 abajo, probablemente rota/muerta**).

**Campos de `products` no cubiertos en los docs existentes:**
- `sku` (string, nullable): existe en DB pero **ningún componente admin lo lee ni lo escribe** —
  solo `product_variants.sku` es editable.
- `offer_date` (string, nullable): existe como prop pública en `CreateProduct` pero **nunca se asigna
  en `save()`**, y no tiene campo en ningún formulario. Muerto.
- `video` (string, nullable): editable solo en `EditProduct` (no existe en `CreateProduct`), input de
  URL libre.
- `position`: usado para el drag-sort de productos dentro de una subcategoría
  (`ShowCategory::updateProductsPosition()`).
- `type_variant`: agregada 2023, **eliminada** en 2026 (`drop_type_variant_from_products_table`) — el
  sistema legacy de variantes (tablas `colors`/`color_product`/etc.) fue reemplazado por el actual
  `product_variants` + `attribute_options`. Una migración vieja (`refac_products_slug`) sigue
  referenciando el modelo `Color` ya eliminado — inofensivo mientras no se reconstruya el esquema
  desde cero sin sembrar esas tablas legacy primero.

**Validación:**
| Campo | CreateProduct | EditProduct |
|---|---|---|
| `name` | required | required |
| `slug` | required\|unique:products | required (**sin `unique` al editar** — colisiones de slug no se validan) |
| `price` | required\|min:2\|numeric | nullable\|numeric (más laxo que crear) |
| `offer_price` | nullable\|lt:price | nullable\|lt:product.price |
| `quantity` | required\|min:0\|numeric | **sin regla — no editable después de crear** |
| `brand_id` | required | nullable |

**Generación de variantes (`EditProduct::generateVariants()`):** producto cartesiano de las opciones
de atributo seleccionadas, slug por combinación (`producto-slug + '-' + opción-slug` por cada
atributo), variantes nuevas siempre parten con `stock = 0` y `sku = null` (el admin debe completarlas
a mano después). Duplicados se detectan **solo por slug** — si ya existe, la combinación se omite
silenciosamente (no actualiza/fusiona).

**Estado — tres mecanismos independientes escriben la misma columna `status`:** el widget de radio
`StatusProduct` (sidebar de edición), el checkbox `ChangeStatusProduct` (por fila en el listado,
reusado también para `ProductVariant`/`FlashOffer` con lógica de persistencia distinta según
`$model`), y `ShowProducts::changeStatus()`/`publishSelected()`/`draftSelected()` (toggle inline y
bulk). `Product::BORRADOR=1`, `PUBLICADO=2`.

**Oferta Flash (`FlashOffer`):** modelo y relación polimórfica (`morphOne`) completos, con
`scopeActive()` (`status=true AND start_at<=now() AND end_at>=now()`). **No existe UI de
creación/programación de ofertas flash en todo el admin** — la única interacción es el toggle
booleano de `status` sobre una `FlashOffer` ya existente (creada por seed/tinker), y ningún componente
del admin instancia `ChangeStatusProduct` con `model=FlashOffer` en la práctica. **Esto hay que
diseñarlo desde cero en la reescritura, no portarlo.**

**Imágenes — confirmación definitiva de qué se usa realmente:** la columna JSON `images` (array
ordenado de IDs de `Image`) en `Product`/`ProductVariant` es lo único activo (ver
`product-workflow.md` §4 para el detalle completo del pool global). La relación polimórfica
`Product::images_morph()` está **explícitamente comentada como legacy en el propio modelo**
(`// LEGACY - Se usará el campo JSON 'images' en su lugar`), y aunque sigue precargándose en algunas
queries (`ShowProducts::render()`, `EditProduct::mount()`), no se usa para renderizar nada.
`Admin\ImageLibrary` y `Admin\GalleryImagesProducts` **duplican exactamente la misma lógica** de
`toggleImage()`/`getItem()` en dos clases separadas — candidato claro a unificar en la reescritura.
El borrado en bloque (`bulkDeleteImages`) busca referencias con `WHERE images LIKE '%"id"%'` (texto
crudo sobre JSON, no `whereJsonContains`) — frágil, a reemplazar por una tabla de asociación real o
un query JSON nativo en la reescritura.

#### Categorías

**Rutas:** `admin.categories.index` (`CategoryController::index`, solo renderiza la vista que embebe
`CreateCategory`), `admin.categories.show` (`ShowCategory`, gestión de subcategorías de una
categoría). No hay ruta dedicada de creación — `CreateCategory` maneja listado + alta + edición +
orden en un solo componente.

- **`categories`**: `name, slug, image (required), position, status (boolean, default 1)`. Sin
  `unique` real en `slug` a nivel de DB (solo a nivel de validación Livewire).
- **`subcategories`**: `name, slug, position, category_id, keywords (json, nullable), status
  (boolean)`. **`Subcategory` no tiene `$casts` para `keywords`** (a diferencia de `images` en
  Product/ProductVariant, que sí usa `array` cast) — cada lectura/escritura hace
  `json_encode`/`json_decode` manual. Keywords son **obligatorias (mínimo 1)** al crear una
  subcategoría (`required|array|min:1`), usadas para SEO (agregadas en Home y en `CategoryFilter`).
- **Marcas por categoría:** `Category belongsToMany Brand` (tabla pivote `brand_category`, sin pivot
  model realmente conectado — existe una clase `BrandCategory` pero no está enlazada vía `->using()`,
  parece vestigial). Una categoría **requiere al menos una marca** al crearse (`brands: required`).
- **Orden (drag-and-drop):** vía Sortable.js + Alpine, no el paquete Livewire Sortable — cada
  `updateXPosition($list)` recorre el payload y persiste `position` uno por uno.
  `Category::getLastPosition()` es un simple `count()+1` — **no es seguro ante huecos por borrados ni
  ante creaciones concurrentes**, puede producir `position` duplicados.
- **Vocabulario de estado distinto al de Productos:** `Category`/`Subcategory` usan
  `NO_PUBLIC=0`/`PUBLIC=1` (no `BORRADOR`/`PUBLICADO`), con su propio componente de toggle genérico
  **`ChangeStatusEntity`** (reusado para ambos modelos) — tres vocabularios de estado distintos
  conviven en el sistema (ver hallazgo #19 en la sección de bugs).
- **Inconsistencia de validación:** el límite de tamaño de imagen es `max:5120` (5MB) al crear una
  categoría pero `max:1024` (1MB) al editarla.

#### Marcas

`Admin\BrandComponent` (ya documentado en detalle en una sesión anterior de este mismo trabajo).
**Bug real encontrado:** `update()` valida unicidad de `name`/`slug` con
`'unique:brands,name,'.$this->brand->name` — la sintaxis `unique:tabla,columna,except` espera el
**id** de la fila a excluir, no el valor anterior de la misma columna. Al pasar el `name` (string, no
numérico) como "except", Laravel lo trata como una búsqueda por PK, que nunca va a coincidir con una
marca real — en la práctica, guardar una marca **sin cambiar su nombre/slug puede rechazarse como
duplicado de sí misma**. Debería ser `$this->brand->id`.

#### Atributos

`Admin\AttributeComponent` (ya documentado en detalle). Complementos:
- `AttributeOption.hex` es opcional, **sin validación de formato** (no hay regex tipo
  `^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`).
- Borrar un `Attribute`/`AttributeOption` es un hard-delete que **cascada a nivel de DB** hacia
  `variant_attribute_option` (FK `onDelete('cascade')`) — puede desasociar silenciosamente atributos
  de variantes de producto ya existentes, sin advertencia explícita en el componente (cualquier
  confirmación sería solo de UI/blade).
- El pivot `variant_attribute_option` (usado por `EditProduct::generateVariants()` para
  `$variant->attributeOptions()->attach($optionIds)`) es la única tabla que conecta
  Atributos/Opciones con variantes reales.

### 2.2 Ventas

#### Órdenes

**`Admin\OrderController`** (distinto del `OrderController` del cliente): `index()` (sin
`$this->authorize()`, calcula 5 contadores por status con queries separadas) y `show(Order $order)`
(**tampoco autoriza** — a diferencia del `show()` del lado cliente, que sí hace
`$this->authorize('author', $order)`). Toda la mutación real ocurre en los componentes Livewire, no
en este controller.

**`Admin\ShowOrders`** (listado + búsqueda):
- **La búsqueda del admin solo matchea `id` (exacto), `contact` (LIKE) y `payment_method`** (strings
  literales `"mercadopago"`/`"yape"` → 1/2). Los scopes `shippingCost`, `total`, `phone`,
  `otherDocnumber`, `extraNote` de `Order` **existen pero están comentados** en la query real — buscar
  por teléfono o DNI del cliente **no funciona** desde esta pantalla pese a que el scope existe.
- **Bug real de precedencia SQL:** el filtro de status (`->where('status', $this->status)`) se agrega
  **después** de una cadena de scopes con `orWhere` sin agrupar entre paréntesis. Como `AND` liga más
  fuerte que `OR` en SQL, al combinar un texto de búsqueda con un filtro de status, las coincidencias
  por `id`/`contact`/`payment_method` **se filtran del status** — es decir, buscar y filtrar por
  status a la vez puede devolver órdenes de status equivocado.
- `Order::scopeOtherContact()`/`scopeOtherPhone()`/`scopeOtherDocnumber()` tienen el cuerpo
  copiado-y-pegado de `scopeContact()`/`scopePhone()`/`scopeDocnumber()` — consultan las columnas
  `contact`/`phone`/`doc_number` en vez de `other_contact`/`other_phone`/`other_doc_number`.
- `editImages()`/`updateImages()`: adjuntar un comprobante/factura a una orden desde el admin
  (`Order::images()` — `morphOne`, un solo archivo por orden, último gana). Al guardar, **envía un
  correo al cliente** (`Mail::to($order->user->email)`, asunto `"Kuchas-kids Comprobante de Pago"` →
  `emails/send-file.blade.php`) con el link de descarga.

**`Admin\StatusOrder`** (detalle — cambio de estado):
- **Bug real, código muerto:** `if ($this->status > 1 && $this->status > 5)` es lógicamente
  equivalente a `$this->status > 5`, que **nunca es verdadero** (status va de 1 a 5). Resultado: marcar
  una orden como RECIBIDO/ENVIADO/ENTREGADO **nunca actualiza `Payment::status` a `APROBADO`** desde
  este flujo — solo la rama `elseif ($this->status == 5)` (ANULADO → `Payment::ANULADO`) es
  alcanzable. Probablemente debía ser `$this->status > 1 && $this->status < 5`.
- **Gap funcional real entre los dos caminos de "anular":** el flujo cliente
  (`App\Http\Controllers\OrderController::annuled()`) envuelve todo en `DB::transaction`, restaura
  stock con `increase()` por cada ítem, y guarda `observation` desde el comentario del cliente. **El
  flujo admin (`StatusOrder::update()`) no hace nada de eso** — sin transacción, sin restaurar stock,
  y sin campo de `observation` en el formulario (aunque la columna existe y se muestra en el detalle
  si `status == 5`). **Anular una orden manualmente desde el admin deja el stock sin restaurar.**
- El radio de cambio de estado en `status-order.blade.php` **no ofrece la opción 1 (PENDIENTE)** —
  solo transiciones hacia adelante (2-4) o directo a anulado (5); un admin nunca puede revertir una
  orden a pendiente desde esta UI.

**Modelo `Order` — relaciones muertas:** `department()`, `city()`, `district()` (`belongsTo`)
referencian columnas (`department_id`, `city_id`/`province_id`, `district_id`) que **no existen** en
la tabla `orders` en ninguna migración — invocarlas lanzaría error de columna desconocida. La
ubicación de envío real vive en la columna JSON `envio` (`department`, `province`, `district`,
`address`, `references`) + el enum `envio_type` (1=recoger en tienda, 2=domicilio, 3=coordinar por
WhatsApp). **Para la reescritura: modelar el envío como el blob `envio` + `envio_type`, no como
relaciones FK reales.**

**Columnas reales de `orders`** (consolidado de migraciones): `id, status, envio_type, shipping_cost,
total, content (json), contact, phone, doc_number, other_contact, other_phone, other_doc_number,
extra_note, coupon_code, discount, user_id, envio (json), payment_method, observation, timestamps`.

#### Cupones

Módulo construido en esta sesión. Componentes:
- **Admin:** `App\Http\Livewire\Admin\CouponComponent` (`app/Http/Livewire/Admin/CouponComponent.php`)
  + vista `resources/views/livewire/admin/coupon-component.blade.php`. Ruta: `admin.coupons.index`
  (`GET admin/coupons`), ítem de menú "Cupones" bajo "Ventas".
- **Cliente:** `App\Http\Livewire\ApplyCoupon` (`app/Http/Livewire/ApplyCoupon.php`) + vista
  `resources/views/livewire/apply-coupon.blade.php`, embebido en `create-order.blade.php`
  (checkout).
- **Servicio de dominio:** `App\Services\CouponService` (`app/Services/CouponService.php`) — toda la
  lógica de elegibilidad/cálculo vive aquí, no en los componentes.
- **Modelo:** `App\Models\Coupon` (`app/Models/Coupon.php`).

**Esquema (`coupons`):**

| Columna | Tipo | Notas |
|---|---|---|
| `code` | string, unique | Se normaliza a MAYÚSCULAS al guardar/buscar. |
| `type` | enum: `category`, `product_group`, `shipping` | Determina qué lógica de `CouponService` aplica. |
| `value` | decimal(8,2) | Monto o porcentaje, según `is_percentage`. |
| `is_percentage` | boolean | |
| `min_purchase_amount` | decimal(8,2), default 0 | Compra mínima del carrito para que el cupón aplique. |
| `expires_at` | timestamp, nullable | Sin expiración si es null. |
| `usage_limit` | unsigned int, nullable | Sin límite si es null. |
| `used_count` | unsigned int, default 0 | Se incrementa en `CreateOrder::create_order()` al confirmar la orden — **no** al aplicar el cupón en el carrito (evita contar cupones que nunca se usan). |
| `is_active` | boolean, default true | Toggle manual desde el admin, independiente de `expires_at`/`usage_limit`. |

**Targeting (`coupon_targets`):** tabla pivote polimórfica (`coupon_id`, `targetable_id`,
`targetable_type`, PK compuesta) — un cupón `category` se asocia a N `Category` vía
`morphedByMany`, uno `product_group` a N `Product`. Un cupón `shipping` no usa esta tabla.

**Scope `Coupon::scopeActive()`:** `is_active = true` AND (`expires_at` null OR `> now()`) AND
(`usage_limit` null OR `used_count < usage_limit`).

**Flujo de aplicación (cliente, `ApplyCoupon::apply()`):**
1. Guard de un-solo-cupón: si `$this->applied` ya es `true`, rechaza con mensaje de error y no
   procesa nada más (endurecido esta sesión — antes solo la UI ocultaba el botón "Aplicar", sin
   validación server-side; ver sección 6).
2. `CouponService::findValid($code)` → `Coupon::active()->where('code', strtoupper(trim($code)))->first()`.
3. `CouponService::apply($coupon, Cart::content())` — despacha por `type`:
   - `category`: filtra ítems del carrito cuya `product->subcategory->category_id` esté en las
     categorías target del cupón (recorre `subcategory`, **no** hay `product->category` directo).
   - `product_group`: filtra ítems cuyo `id` esté en los productos target.
   - `shipping`: no filtra ítems, solo marca `shipping_free = true` si se cumple `min_purchase_amount`.
   - Todas validan `min_purchase_amount` contra el subtotal del carrito antes de aplicar.
4. Éxito → emite evento Livewire `couponApplied` con `discount_amount`, `shipping_free`, `coupon_code`.

**Consumo en checkout (`CreateOrder`):** escucha `couponApplied`/`couponRemoved` vía
`protected $listeners`, guarda `discount_amount`/`coupon_shipping_free`/`coupon_code` como propiedades
del componente. Al confirmar la orden: `order->discount`, `order->coupon_code` se persisten,
`order->total = shipping_cost + subtotal - discount`, y se incrementa `Coupon::used_count` **solo si**
`coupon_code` no está vacío.

**Mostrado en:** resumen de checkout (`create-order.blade.php`), página de pago
(`payment-order.blade.php`), y el correo "Nueva Venta" al admin (`emails/new-sale.blade.php`) — los
tres muestran el mismo desglose Subtotal → Envío → Descuento (cupón) → Total, reconstruyendo el
subtotal pre-descuento como `total - shipping_cost + discount` (porque `total` ya está neto de
descuento).

**Bug histórico ya corregido:** `apply-coupon.blade.php` originalmente envolvía los botones
`wire:click="apply"`/`wire:click="remove"` en `<template x-if>` de Alpine — Alpine destruye/recrea
esos nodos DOM, lo que rompe el listener de eventos de Livewire (el clic nunca llegaba al servidor).
Se reemplazó por `@if`/`@else` de Blade (server-side, sin ese conflicto). **Regla general para este
proyecto:** nunca envolver un elemento con `wire:click`/`wire:model` dentro de `x-if` — usar `x-show`
o condicionales de Blade.

#### Zonas

**`Admin\DeliveryZone`** (`app/Http/Livewire/Admin/DeliveryZone.php`):
- `Zone` solo tiene `name`, `cost`. `District belongsTo Zone` (`zone_id` nullable). La asignación de
  distritos a una zona es **unidireccional y destructiva**: `save()`/`update()` recorren los IDs de
  distrito marcados y hacen `District::find($id)->zone_id = $zone->id` — **sin verificar si ese
  distrito ya pertenecía a otra zona** (se lo "roba" en silencio, sin confirmación).
- **Bug real:** al editar una zona, los distritos listados en el checkbox son **todos los distritos
  existentes**, no solo los libres (pese a que el texto vacío dice "Sin distritos libres de zonas") —
  y **desmarcar un distrito durante la edición no lo desasocia**, solo se procesan las adiciones
  nuevas. `delete(Zone $zone)` sí desasocia correctamente todos sus distritos primero
  (`update(['zone_id' => null])`) antes de borrar la zona.
- **Inconsistencia de validación:** costo máximo `1000` al crear, `100` al editar.
- `update()` pasa el array `editForm` completo a `$this->editZone->update($editForm)`, incluyendo
  claves (`districts`, `open`) que no son columnas de `Zone` — no falla porque `Zone::$fillable =
  ['name','cost']` las descarta silenciosamente, pero es un code smell (debería pasar el array
  explícito).
- **Resolución del costo de envío (confirmado extremo a extremo):** admin crea `Zone` con costo fijo →
  asigna distritos → en checkout, `CreateOrder.php` resuelve `Zone::find($district->zone_id)->cost`
  según el distrito elegido por el cliente. **Un distrito con `zone_id = null` rompe el checkout para
  ese distrito** (`Zone::find(null)->cost` sobre null) — todo distrito debe tener zona asignada para
  que el checkout funcione.
- Modelos geográficos: `Department`/`Province`/`District` usan **claves primarias no numéricas**
  (`$keyType = 'string'`, estilo ubigeo peruano). **Typo real en `Province::$fillable`:**
  `deparment_id` (falta la segunda "t") en vez de `department_id` — cualquier mass-assignment que
  intente setear `department_id` fallará silenciosamente contra esa clave mal escrita.
  `Department`/`Province` también declaran una relación `orders()` — **muerta**, mismo motivo que
  `Order::department()`/`city()`/`district()` (sin columnas FK reales en `orders`).
- La migración `create_districts_table` tiene el cuerpo de `Schema::create` **completamente
  comentado** — la tabla `districts` que existe hoy en producción no la creó esa migración; su origen
  real no es reconstruible solo desde el historial de migraciones (hay un `DistrictSeeder`, posible
  fuente real). **Advertencia para la reescritura:** no asumir que `php artisan migrate:fresh` desde
  cero reproduce el estado actual de esta tabla.

### 2.3 Contenido

#### Banners
Documentado en profundidad en **[docs/banner-workflow.md](banner-workflow.md)** (validación de
dimensiones 731×316, defensa anti-500 en subida rota, eliminación). Ver también
`docs/upload-fix-php83.md` para el contexto de por qué las subidas fallaban en el entorno.

**Hallazgos adicionales de esta investigación:**
- **Bug real en la migración `create_banners_table`:** el guard de creación de tabla verifica
  `Schema::hasTable('settings')` en vez de `Schema::hasTable('banners')` (copy-paste). Como `settings`
  ya existe para cuando esta migración corre, la condición `!hasTable('settings')` es falsa y
  **`Schema::create('banners', ...)` nunca se ejecuta** en una migración desde cero — riesgo real de
  despliegue si alguna vez se reconstruye la base de datos con `migrate:fresh` (la tabla en
  producción hoy existe porque se creó por otra vía).
- La columna `banners.position` existe pero **no se lee ni se escribe en ningún lado** — no hay
  reordenamiento de banners implementado pese a que la columna sugiere que se planeó.
- `BannerHome.php` (storefront) hace `Banner::all()` sin filtro de estado activo/inactivo ni rango de
  fechas — **todo banner subido queda visible indefinidamente hasta que se borra manualmente**, no
  hay concepto de "banner programado" o "banner pausado".
- Sin edición/reemplazo in-place — solo agregar nuevo o borrar.

### 2.4 Administración

#### Usuarios

**`Admin\UserComponent`:** el sistema tiene **un único rol de Spatie: `admin`** (sembrado una sola vez
en `UserSeeder`). No existen otros roles en todo el código — "Cliente" en la UI es simplemente la
**ausencia** del rol `admin`, no un rol real. `assignRole($user, $value)`: `$value == 1` asigna
`admin`, cualquier otro valor lo remueve. El toggle es un **radio inmediato sin confirmación**
(`wire:change`, dispara al instante) — a diferencia de borrar zonas/banners, que sí piden confirmación
vía SweetAlert2. El listado **excluye siempre al admin actualmente logueado**
(`where('email', '<>', auth()->user()->email)`) — no puede autogestionarse desde esta pantalla, pero
sí podría revocarle el rol a cualquier otro admin con un solo clic sin aviso.

El único gate de todo `/admin/*` es el middleware `role:admin,web` en
`RouteServiceProvider::mapAdminRoutes()`. El redirect post-login según rol (`hasRole('admin')` →
`admin.index`, si no → `welcome`) está **duplicado** en `FortifyServiceProvider` (override de
`LoginResponse`) y en `RedirectIfAuthenticated` — mismo chequeo escrito dos veces.

#### Configuración

**`Setting`** — fila única (`Setting::first()`, la app asume que existe exactamente una). Columnas
reales: `min_amount, headband_one, headband_two, show_headband, logo, whatsapp, email_receive,
email_client, company_info (json)`.

- **`business_name`, `trade_name`, `ruc` no son columnas propias** — son 3 llaves dentro del JSON
  `company_info`, leídas/escritas por `SettingsComponent` como si fueran campos planos del formulario.
- **`logo` se edita en un componente Livewire separado**, `Admin\UpdateLogoImage`, embebido al final
  de la misma pantalla de Configuración — no forma parte de `SettingsComponent`/su `$editForm`. Sin
  restricción de `dimensions` (a diferencia de banners, que sí la exigen).
- `min_amount` (el monto mínimo de compra para envío gratis, ya conocido del checkout) se valida solo
  `numeric|min:0` — no es `required`, puede quedar vacío/null.
- **Efecto secundario real:** `updatingShowHeadband()` — al desactivar el headband, fuerza
  `editForm['min_amount'] = 0` y **guarda inmediatamente todo el formulario** (`update()` completo,
  no solo el campo del checkbox). Si el admin tenía otros campos a medio editar sin guardar en ese
  momento, también se persisten como efecto colateral del toggle.
- Las dos secciones visuales del formulario (monto/headband vs. datos de contacto) son en realidad
  **un solo formulario** — ambas llaman al mismo método `update()`, que persiste el `$editForm`
  completo sin importar cuál botón "Guardar" se presionó.

---

## 3. Cliente / Storefront

### 3.1 Home

**`WelcomeController::__invoke()`** (`GET /`, `app/Http/Controllers/WelcomeController.php`):
1. **SEO:** agrega `keywords` (JSON) de **todas** las subcategorías (sin filtrar por status) en una
   sola cadena deduplicada, llama `setSEOTools(null, $description)` (sin título ni URL canónica
   explícitos). Hay líneas comentadas que antes también sembraban SEO desde nombres de categorías y
   productos — código muerto, no se ejecuta.
2. **Banner de órdenes pendientes** (solo logueado): `Order::where('status', 1)
   ->whereNull('payment_method')->where('user_id', ...)`. Si hay resultados, `session()->flash(
   'flash.banner', "Usted tiene N órdenes pendientes . Ir a pagar")` enlazando a
   `orders.index?status=1`.
3. Carga `Category::orderBy('position')->where('status', Category::PUBLIC)->get()` → vista `welcome`.

**`welcome.blade.php`:** `@livewire('banner-home')` + loop de categorías, cada una renderizada **solo
si** `count($category->products)` (relación `hasManyThrough(Product, Subcategory)`) es verdadero —
**esta condición no filtra por `Product::PUBLICADO`**, a diferencia del resto del sitio; una categoría
puede mostrarse aunque todos sus productos estén en borrador, mientras exista al menos una fila.
Cada sección embebe `@livewire('category-products', ['category' => $category])`.

**`BannerHome.php`:** `Banner::all()` sin filtro de status ni orden — trivial.

**`CategoryProducts.php`** (reusado también como "productos relacionados" en detalle de producto,
pasando `$product` para excluirlo de los resultados):
- `loadProducts()` (carga diferida, patrón skeleton→load): si `$category` es null, cae a
  `Category::all()->random()` (código de fallback no usado desde Home).
- Query: `$category->products()->where('products.status', Product::PUBLICADO)`, eager-load
  `subcategory`, `variants` (solo `status=true`), `variants.attributeOptions` (solo atributo Color).
  Orden: `subcategories.position` luego `position` del producto.
- Pre-calcula por producto (evita N+1 en la card): `card_colors` (opciones de color únicas entre
  variantes), `card_min_price`/`card_has_variants` (si hay variantes: `min(price)` vs `min(offer_price)`
  donde `offer_price > 0`, usa la oferta solo si es menor; si no hay variantes, `offer_price ?: price`).
- Al terminar, emite evento Livewire `glider` (con el id de categoría) que el script en
  `welcome.blade.php` escucha para inicializar el carrusel Glider.js correspondiente
  (`.glider-{id}`, breakpoints 640/768/1024/1280 — detalle completo en `docs/product-view.md` para el
  resto de sliders del sitio).

### 3.2 Búsqueda

Dos superficies comparten la misma lógica de backend:
- **Dropdown del navbar:** `App\Http\Livewire\Search` (`app/Http/Livewire/Search.php`) + vista
  `resources/views/livewire/search.blade.php` — resultados en vivo mientras se escribe.
- **Página completa:** `App\Http\Controllers\SearchController` (ruta `GET search`) + vista
  `resources/views/search.blade.php` con `<x-product-list>`.

**Query compartida — `App\Traits\ProductScopes::scopeSearch()`** (`app/Traits/ProductScopes.php`,
usado por el modelo `Product`):

```php
$query->where('products.status', Product::PUBLICADO)
    ->when($search, function ($query) use ($search, $columns) {
        $query->where(function ($query) use ($search, $columns) {
            $query->where(DB::raw("CONCAT_WS(''," . implode(',', $columns) . ")"), 'LIKE', "%$search%")
                ->orWhereHas('variants', fn ($query) => $query->where('sku', 'LIKE', "%$search%"));
        });
    });
```

- Columnas concatenadas: `slug`, `sku`, `price`, `offer_price`, `description` (del producto base).
- **Extendido esta sesión:** ahora también matchea por `product_variants.sku` vía `orWhereHas` —
  antes, un producto cuyo único SKU real vivía en sus variantes (común, ya que el flujo de creación
  de variantes genera SKUs por combinación, ver `product-workflow.md` §3.2) era invisible al buscador.
  El filtro `status = PUBLICADO` se mantiene como AND de todo el grupo (verificado que un producto en
  borrador con una variante que matchea el texto de búsqueda **no** se filtra igual).

**Precio mostrado en resultados:** antes usaba `$product->offer_price` del producto base incluso para
productos con variantes (mostraba precios desactualizados/incorrectos si la oferta real estaba solo en
una variante). Corregido: si `$product->variants->count() > 0`, se muestra `"Desde S/ " .
$product->getMinPrice()` (mismo patrón ya usado correctamente en `product-card.blade.php` para los
carruseles de categoría) en vez del precio crudo del producto base.

### 3.3 Categorías / listado de productos

**`CategoryController::show(Category $category)`** (`GET categories/{category}`, route-model-bound
por `slug`): trivial, solo `return view('categories.show', compact('category'))`. **No verifica
`Category::PUBLIC`** — a diferencia de Home y de `CategoryFilter` (abajo), una categoría no-pública
sigue siendo visible si se conoce/adivina el slug.

**`CategoryFilter.php`** (renderizado desde `categories.show`, usa `<x-product-list>`):
- **Filtros disponibles: solo subcategoría y marca.** No hay filtro de rango de precio, ni por
  atributo (talla/color), ni orden configurable. `protected $queryString = ['subcategoria', 'marca',
  'page']` — los tres reflejados en la URL.
- `updatingSubcategoria()`: `resetPage()`, `showButton = true`, emite `downPage` (scroll). *
  `updatingMarca()`: mismo reset pero **sin** emitir `downPage` — inconsistencia menor entre ambos
  handlers.
- **Paginación manual, no SQL:** `render()` trae **todos** los productos que matchean el filtro con
  `->get()`, ordena en PHP (`->sortBy([['subcategory.position'], ['position']])`) y recorta con
  `->slice($startIndex, $perPage)`, envolviendo el resultado en un `LengthAwarePaginator` solo para
  los links de paginación. **Sin `LIMIT`/`OFFSET` real** — riesgo de escalabilidad si una categoría
  crece mucho; a tener en cuenta explícitamente al portar esto a un endpoint de API paginado de
  verdad.
- Misma lógica de pre-cómputo `card_colors`/`card_min_price`/`card_has_variants` que
  `CategoryProducts.php` — **duplicada entre ambos componentes**, candidato claro a un
  "product card view-model" compartido en la reescritura.
- `mount()` arma una URL canónica para SEO que **incluye los query params de filtro/paginación
  actuales** (`setSEOTools($category->name, $description, $url)`) — antipatrón SEO (riesgo de
  contenido duplicado indexado); vale la pena corregirlo en la reescritura, no necesariamente
  replicarlo.

### 3.4 Detalle de producto

Documentado en `docs/flow.md` (selección reactiva de variantes, filtrado inteligente de
tallas/colores según stock, prioridad de precios) y `docs/product-view.md` (dependencias de sliders:
Swiper para galería desktop, FlexSlider para mobile, Glider.js para relacionados).

**Componente:** `App\Http\Livewire\ProductDetail` (`app/Http/Livewire/ProductDetail.php`). Resuelve
precio/oferta/stock según variante seleccionada (`loadInitialState()`/`updateSelection()`), con
`checkFlashOffer()` priorizando oferta flash de la variante > oferta flash del producto > oferta
normal > precio base — mismo orden que `applyOffer()` en `app/helpers.php` (ver sección 5).

### 3.5 Carrito

Documentado en profundidad en **[docs/cart-workflow.md](cart-workflow.md)**.

**Actualización posterior a ese doc (esta sesión) — validación de stock ahora es server-side, no solo
de UI:**
- `AddCartItem::increment()`/`decrement()`/`addItem()` (`app/Http/Livewire/AddCartItem.php`) ahora
  hacen `min()`/`max()` contra `$this->quantity` (stock disponible) antes de mutar `$this->qty` o
  llamar `Cart::add()`. Antes, el único guard era el atributo `disabled` de Alpine en el botón — un
  usuario podía llamar el método Livewire directamente (ej. devtools) y agregar más unidades de las
  que hay en stock.
- `UpdateCartItem::increment()`/`decrement()` (`app/Http/Livewire/UpdateCartItem.php`) — mismo fix,
  clamp contra `$this->quantity` (calculado en `mount()` como stock físico menos lo que otros ítems
  del carrito ya reservan, sumando de vuelta la cantidad de esta línea).

Ninguno de los dos endurecimientos cambia el comportamiento visible para un uso normal — el resultado
final coincide con lo que la UI ya prevenía visualmente; ahora también está garantizado del lado del
servidor.

### 3.6 Checkout

**Componente:** `App\Http\Livewire\CreateOrder` (`app/Http/Livewire/CreateOrder.php`) + vista
`resources/views/livewire/create-order.blade.php`. Ruta `GET orders/create` (auth).

**`create_order()` — orden de operaciones:**
1. Arma `$rules` dinámicamente según: `other_person` (datos de un tercero que recibe), `envio_type`
   (1 = recoger en tienda, 2 = envío a domicilio → requiere departamento/provincia/distrito/dirección/
   referencias, 3 = "otro destino"/coordinación externa), `facturacion` (requiere RUC/razón social/
   dirección).
2. `$this->validate($rules)`.
3. **Guard de stock (nuevo esta sesión, el más crítico):** recorre `Cart::content()` y compara
   `current_quantity($item->id, $variantId)` contra `$item->qty`. Si algún ítem excede el stock
   **real y actual** (no el capturado cuando se agregó al carrito), aborta con
   `$this->addError('stock', "No hay stock suficiente para \"...\". Disponible: N.")` y no continúa.
   Este es el escenario real que motivó el fix: un cliente agrega N unidades cuando hay stock
   suficiente, el stock baja por otra venta mientras completa el checkout, y sin este guard la orden
   se creaba igual y `discount()` dejaba el stock en negativo (ver sección 6).
4. Construye `Order`, calcula `shipping_cost` (0 si recoger en tienda, 0 si el cupón trae envío
   gratis, 0 si el subtotal supera `Setting::min_amount`, si no el costo de la `Zone` del distrito
   elegido — ver sección 2.2 Zonas), aplica descuento de cupón al `total`.
5. Guarda `Invoice` si `facturacion` está activo.
6. `foreach (Cart::content() as $item) discount($item);` — descuenta stock real (ver `app/helpers.php`
   en sección 5). Como el paso 3 ya garantizó que hay stock suficiente para todo el carrito, esto no
   debería poder llevar el stock a negativo salvo condición de carrera entre el guard y este bucle
   (ventana muy pequeña, no mitigada — ver sección 6 para el análisis de riesgo residual).
7. `Cart::destroy()`, redirige a `orders.payment`.

### 3.7 Pago

**Componente:** `App\Http\Livewire\PaymentOrder` (`app/Http/Livewire/PaymentOrder.php`) + vista
`resources/views/livewire/payment-order.blade.php`. Ruta `GET orders/{order}/payment` (auth,
autorizado por policy `author`+`payment` sobre el `Order`).

**Dos métodos de pago:**

1. **Izipay** (tarjeta): al renderizar la vista, un bloque `@php` hace `Lyra\Client::setDefault*()`
   (credenciales desde `config('services.izipay.*')`) y `$client->post('V4/Charge/CreatePayment', ...)`
   para obtener un `formToken`, que alimenta el widget embebido Krypton
   (`kr-payment-form.min.js`). Al completar el pago, Krypton redirige a `orders.izipay` (`POST
   orders/izipay` → `OrderController::izipay()`), y el gateway también notifica de forma independiente
   a `routes/web.php: POST webhooks` (`WebhooksController`).
   - **Bug corregido esta sesión:** el bloque `@php` llamaba `header('Authorization', '<token>')` y
     `header('Content-Type', 'application/json')` manualmente — uso inválido de `header()` (espera un
     solo string `"Nombre: Valor"`, no dos argumentos), que generaba un header CGI malformado y
     tumbaba el proceso PHP antes de que Laravel pudiera renderizar cualquier error (Apache devolvía
     su 500 genérico, sin nada útil en `storage/logs/laravel.log`). Además era código muerto: 
     `Lyra\Client::post()` ya arma su propio header `Authorization: Basic ...` internamente a partir
     de `setDefaultUsername`/`setDefaultPassword`. Se eliminaron esas dos líneas.
2. **Yape** (manual): el cliente sube una foto del comprobante
   (`wire:model.lazy="photo"`, `Livewire\WithFileUploads`). `PaymentOrder::saveYape()` valida
   (`mimes:png,jpeg,jpg|max:2048`), guarda el archivo (`$this->photo->store('photos')`), crea
   `Payment` + `Image` (relación polimórfica `imageable`), limpia uploads temporales viejos
   (`cleanupOldUploads()` — borra `livewire-tmp` con más de 10s), y llama `payOrder()`.
   - **UI mejorada esta sesión:** el input de archivo nativo (feo, "Choose File / No file chosen") se
     reemplazó por un dropzone con drag & drop, preview de imagen (`FileReader` client-side, no espera
     el roundtrip de Livewire), nombre de archivo, botón de quitar y overlay de carga
     (`wire:loading` sobre `wire:target="photo"`). Lógica Alpine extraída a `Alpine.data('yapeUpload',
     ...)` registrada en `alpine:init` (convención del proyecto para lógica Alpine no trivial), no
     inline en `x-data`.

**`payOrder()`** (común a ambos métodos tras confirmar pago): `order->status = RECIBIDO`,
`order->payment_method`, `payment->status = APROBADO`, envía correo "Nueva Venta" al admin (ver 3.8),
redirige a `orders.show`.

**Resumen de precios en esta página:** mismo patrón que checkout — Subtotal (reconstruido) → Envío →
Descuento (cupón, si aplica, verde) → Pago. Corregido esta sesión (antes "Subtotal" mostraba el monto
ya neto de descuento, mal etiquetado).

### 3.8 Emails transaccionales

**`App\Mail\MessageRecieved`** (`app/Mail/MessageRecieved.php`) despacha por `$subject` a dos vistas:
- `"Nueva Venta Página web Kuchas Kids"` → `emails/new-sale.blade.php` (al admin, `Setting::email_receive`,
  disparado desde `PaymentOrder::payOrder()`).
- `"Kuchas-kids Comprobante de Pago"` → `emails/send-file.blade.php` (link de descarga de un archivo,
  flujo separado, sin desglose de precios).

**Bug corregido esta sesión en `new-sale.blade.php`:** la plantilla hacía `$order = $msg['order'];` al
inicio, pero `Mailable::with($array)` de Laravel **aplana** las claves del array directamente como
variables de vista — nunca existió una variable `$msg` en el contexto de la vista. Esa línea
sobrescribía silenciosamente el `$order` real (correctamente inyectado) con `null`, rompiendo *todo*
el correo (envío, contacto, ítems, total — no solo el descuento, que fue el síntoma que lo detectó).
Se eliminó la línea; `$order` ya llega directo. Se añadió el mismo desglose Subtotal/Envío/Descuento/
Total que checkout y pago.

### 3.9 Cuenta de usuario / Auth

Scaffolding estándar de Jetstream/Fortify (registro, 2FA, sesiones, tokens API — sin lógica de
negocio custom encima, no se profundiza aquí). La única customización real es el **redirect
post-login según rol**, implementada en **dos lugares distintos con la misma regla duplicada**:

1. `FortifyServiceProvider::register()` — override inline (clase anónima) del contrato
   `Laravel\Fortify\Contracts\LoginResponse`:
   ```php
   $url = $request->user()->hasRole('admin') ? route('admin.index') : route('welcome');
   return redirect()->intended($url);
   ```
2. `app/Http/Middleware/RedirectIfAuthenticated.php` — mismo chequeo `hasRole('admin')`, aplicado
   cuando un usuario ya logueado visita una ruta de invitado (ej. `/login` de nuevo).

**Nota para la reescritura:** centralizar esta regla (admin → panel admin, cliente → home) en un solo
lugar en vez de mantenerla duplicada.

### 3.10 Órdenes del cliente

**`OrderController`** (`app/Http/Controllers/OrderController.php`), todas dentro del grupo `auth`:

- **`index()`** (`GET orders`): lista las órdenes del usuario logueado, filtrable por `?status=`
  (query string cruda). Calcula 5 badges de conteo (una query por status: PENDIENTE/RECIBIDO/ENVIADO/
  ENTREGADO/ANULADO) — sin paginación en el listado en sí.
- **`show(Order $order)`** (`GET orders/{order}`): autoriza con `OrderPolicy::author()`
  (`$order->user_id == $user->id`, comparación floja). Decodifica `content`/`envio` (JSON) para la
  vista.
- **`annuled(Order $order, Request $request)`** (`POST orders/{order}/annuled`): **sin
  `$this->authorize()`** — a diferencia de `show()`, no hay chequeo de ownership explícito en este
  método. Dentro de una transacción: marca `ANULADO`, guarda `observation` (comentario del cliente),
  **restaura stock** vía `increase($item)` por cada línea del pedido (inverso exacto de `discount()`
  usado en checkout), marca el `Payment` asociado como `ANULADO`. Responde con el string plano `'ok'`
  o, en el `catch`, con el mensaje crudo de la excepción — sin contrato JSON ni código de estado
  diferenciado.
- **`pay()`** (`GET orders/pay`) — **comentado explícitamente como "solo para desarrollo"**. Integra
  directo con la API de MercadoPago (`GET https://api.mercadopago.com/v1/payments/{id}`) usando **un
  access token de MercadoPago hardcodeado en el código fuente**. Este flujo de MercadoPago parece
  legacy/inactivo — el checkout real (`PaymentOrder.php`) solo ofrece Izipay + Yape.
- **`izipay(Request $request)`** (`POST orders/izipay`, **exento de CSRF** — necesario porque el JS
  embebido de Izipay hace el POST directamente) — **el callback más crítico del sistema de pagos**:
  - Busca la orden por `Order::find($request->order_id)` (campo plano del request, **no**
    route-bound) → `$order` puede ser `null`.
  - Decodifica `kr-answer` (payload de Izipay) → `$status = $response->orderStatus`.
  - **Bug real y alcanzable:** la rama `else` (cuando `$order` es null) hace
    `Payment::find('order_id', $order->id)` — dereferencia `$order->id` sobre un `$order` que ya se
    sabe `null` en esa rama → **error fatal**. Un `order_id` ausente o inválido en el POST de Izipay
    tumba este endpoint en vez de fallar con gracia.
  - Mapea `orderStatus` (`PAID`/`RUNNING`/`UNPAID`) a `Order.status`/`Payment.status` — **sin rama
    `default`**; cualquier otro valor deja `$mensaje` indefinida.
  - **Sin verificación de firma/HMAC** del payload `kr-answer` — la única protección es la exención de
    CSRF combinada con requerir sesión autenticada (`auth` middleware).
  - Marca `payment_method = 1` — el comentario en `pay()` documenta `1` como "MercadoPago", pero aquí
    (el flujo realmente en uso) `1` significa Izipay. **Inconsistencia semántica real entre ambos
    métodos.**
  - Al confirmar pago: envía el mismo correo "Nueva Venta" (`MessageRecieved`, ver 3.8) y redirige a
    `orders.show`.
- **`orderFailure()`** (`GET orders/failure`): cuerpo mayormente código muerto comentado; la lógica
  viva flashea un mensaje de tarjeta rechazada y **retorna `view('welcome')` directamente sin pasar
  `$categories`** — como `welcome.blade.php` itera `$categories` sin fallback, esta ruta
  probablemente rompe con "Undefined variable" al visitarla.

**`WebhooksController`** (`app/Http/Controllers/WebhooksController.php`) — **código muerto en su
totalidad**: el único método (un webhook de MercadoPago) está 100% comentado, pero la ruta sigue
registrada:
```php
Route::post('webhooks', [WebhooksController::class])->name('webhooks.pay');
```
Esta forma de array de un solo elemento no es una `[Clase::class, 'metodo']` válida y la clase no
tiene `__invoke()` — la ruta probablemente falla al despacharse. Además vive dentro del grupo `auth`,
lo cual de por sí es incompatible con ser un webhook real de un gateway externo (no llevaría cookie de
sesión). **El callback real y funcional es `OrderController::izipay()`, no este webhook.**

**Constantes de estado:**
- `Order`: `PENDIENTE=1, RECIBIDO=2, ENVIADO=3, ENTREGADO=4, ANULADO=5` (comentario en el modelo:
  "vigente por 15 minutos" — el vencimiento de órdenes pendientes no se aplica en `OrderController`,
  presumiblemente se maneja en `CreateOrder.php`).
- `Payment`: `APROBADO=1, PENDIENTE=2, RECHAZADO=3, ANULADO=4`.
- `OrderPolicy::payment()`: `$order->status == 1` (solo órdenes PENDIENTE son pagables) — declarada
  pero no se invoca dentro de `OrderController`; se usa desde `PaymentOrder.php`.

### 3.11 Páginas secundarias

- **`ContactForm.php`** (`GET contact`): formulario simple (contacto/email/teléfono/mensaje),
  envía **síncrono** (`Mail::send`, no `queue`) `ContactNotification` a `Setting::email_receive`. Sin
  protección anti-spam (sin honeypot/captcha/throttle).
- **`ComplaintsBook.php`** (`GET libro-de-reclamaciones` — Libro de Reclamaciones, obligatorio por
  regulación peruana de protección al consumidor): campos incluyen `type` restringido a
  `in:Reclamo,Queja` (las dos categorías que exige la norma). Envío también síncrono. Nota de
  implementación: tras `reset()` reasigna `type = 'Reclamo'` manualmente (si no, quedaría `null` y
  rompería la regla `in:` en el siguiente envío).
- **`SecondaryPagesController::markdownPage(string $page)`** (`GET info/{page}`): resuelve
  `resources/markdown/pages/{page}.md` directo desde el segmento de ruta (**sin allow-list
  explícita**, solo `file_exists`) — páginas reales existentes: `frequent-questions`,
  `privacy-policy`, `shipping-policies`, `terms-and-conditions`. Antes de convertir a HTML
  (`Str::markdown()`), hace `str_replace` de placeholders literales
  (`{{whatsapp}}`, `{{email_client}}`, `{{email_receive}}`, `{{business_name}}`, `{{trade_name}}`,
  `{{ruc}}`) contra `Setting` — el título de la página se extrae con regex del primer `# Heading` del
  markdown (post-sustitución), con fallback a título-desde-slug si no hay heading.

---

## 4. Modelo de datos — relaciones clave

- `Product belongsTo Brand`, `belongsTo Subcategory`; `Subcategory belongsTo Category`. La
  elegibilidad de cupones por categoría recorre `product->subcategory->category_id`, **no** hay
  `product->category` directo.
- `Product hasMany ProductVariant`. `Product::getStockAttribute()` suma `variants.stock` si existen,
  si no cae a `quantity`.
- Imágenes: columna JSON `images` (array ordenado de IDs de `Image`) en `Product` y `ProductVariant`
  — la relación polimórfica `imageable()` es **legacy**, no se usa para resolver qué imagen pertenece
  a qué producto (ver `product-workflow.md` §4 para el detalle completo del pool global de imágenes).
- `Order` no tiene relación Eloquent a `Coupon` — `coupon_code`/`discount` son un snapshot inmutable
  al momento de la compra (correcto para e-commerce: si el cupón se edita/borra después, la orden ya
  facturada no debe cambiar).
- `Order hasOne Payment`, `hasOne Invoice`, `morphOne Image` (comprobantes de pago Yape).

---

## 5. Funciones de soporte globales (`app/helpers.php`)

Autoloaded globalmente vía `composer.json` → `autoload.files`. Todas verificadas empíricamente esta
sesión (valores reales de DB/carrito, no solo lectura de código):

| Función | Qué hace |
|---|---|
| `current_quantity($productId, $variantId = null)` | Stock físico actual en DB. |
| `qty_added($productId, $variantId = null)` | Cuánto de ese producto/variante hay ya en el carrito de la sesión actual. |
| `qty_available($productId, $variantId = null)` | `current_quantity - qty_added`. |
| `discount($item)` | Descuenta stock (variante o producto) al confirmar una orden. |
| `increase($item)` | Inverso de `discount()` — restaura stock al anular una orden (`OrderController::annuled`). |
| `applyOffer($item)` | `[base_price, price]` — prioridad: oferta flash activa > `offer_price` (si no expiró) > precio base. |
| `setSEOTools($entity_name, $description, $url)` | Configura `SEOTools` (canonical/title/description) según la ruta actual. |

`findProduct()` existía pero era código muerto (cero llamadas en todo el repo) — **eliminado** esta
sesión, junto con el `use App\Models\ProductVariant;` que quedó huérfano.

---

## 6. Bugs y deuda técnica encontrados

Relevante para la reescritura: estas son reglas de negocio que **no** están escritas en ningún lado
más que en el código (o estaban rotas), así que hay que portarlas explícitamente, no asumir que el
comportamiento actual observado en producción es siempre el correcto.

### 6.1 Corregidos esta sesión

1. **Sin validación de stock server-side en todo el flujo carrito→checkout.** El único guard era
   `x-bind:disabled` de Alpine (cosmético). Corregido con clamps en `AddCartItem`/`UpdateCartItem` +
   guard de re-validación en `CreateOrder::create_order()` justo antes de descontar stock. Riesgo
   residual: ventana de condición de carrera entre el guard (paso 3) y el bucle `discount()` (paso 6)
   si dos checkouts concurrentes pasan el guard al mismo tiempo — no mitigado (requeriría locking
   pesimista o `decrement()` atómico con chequeo, fuera de alcance de este pase).
2. **`ApplyCoupon::apply()` sin guard server-side contra doble aplicación** — mismo patrón, corregido
   con chequeo explícito de `$this->applied` al inicio del método.
3. **`wire:click` dentro de `<template x-if>`** rompe el listener de Livewire (Alpine destruye/recrea
   el nodo). Encontrado en `apply-coupon.blade.php`, causaba que el botón "Aplicar" cupón no hiciera
   nada. Regla general para todo el proyecto: usar `x-show`, no `x-if`, quien envuelva un
   `wire:click`/`wire:model`.
4. **`header('Authorization', ...)` con firma inválida** en `payment-order.blade.php` tumbaba el
   proceso PHP con un header CGI malformado antes de que Laravel pudiera mostrar cualquier error.
5. **`$order = $msg['order']` en `new-sale.blade.php`** sobrescribía con `null` una variable de vista
   ya correctamente inyectada por `Mailable::with()`, rompiendo silenciosamente el correo completo
   (no logueaba nada útil — el error real quedaba enmascarado por los `@if`/`@php` que fallaban en
   cascada sobre un `$order` nulo).
6. **Búsqueda no indexaba SKU de variantes**, solo del producto base — invisibilizaba productos cuyo
   único SKU real vive en sus variantes.
7. **Precio de productos con variantes mal mostrado** en buscador (dropdown y página completa): usaba
   `$product->offer_price` del producto base en vez de `getMinPrice()` — mostraba precios
   desactualizados que no reflejaban ofertas reales a nivel de variante.
8. **Menú admin sin colapsar y sin soporte responsive real** — sidebar fijo `w-64`, sin drawer mobile,
   sin botón de colapso. Corregido con un shell Alpine (`adminShell()`, registrado en
   `layouts/admin.blade.php`) que maneja `collapsed` (persistido en `localStorage`) y `mobileOpen`
   (transitorio), rediseño del menú a modo icon-rail colapsado con flyouts en hover para desktop, y
   drawer off-canvas con backdrop en mobile.

### 6.2 Encontrados en esta auditoría

> **Actualización:** la mayoría de los ítems de esta lista (los acotados/de corrección clara) ya se
> corrigieron en una pasada posterior de esta misma sesión — ver **[docs/incidencias.md](incidencias.md)**
> para el detalle de qué archivo se tocó y cómo. Los que quedaron **sin tocar a propósito** (feature
> nueva o refactor grande de arquitectura, no bugs acotados) están listados al final de ese mismo
> archivo. La lista completa de hallazgos originales se conserva abajo tal cual se encontró, como
> registro de la auditoría.

**Pagos / integridad de datos (los más críticos):**
9. `OrderController::izipay()` — en la rama `else` (`$order` no encontrado), hace
   `Payment::find('order_id', $order->id)` sobre un `$order` que ya se sabe `null` → error fatal
   alcanzable con un `order_id` inválido/ausente en el POST de Izipay.
10. `OrderController::izipay()` no verifica firma/HMAC del payload `kr-answer` — solo se apoya en la
    exención de CSRF + sesión autenticada.
11. `OrderController::izipay()` no tiene rama `default` para valores de `orderStatus` no reconocidos.
12. `payment_method = 1` significa "Izipay" en el código real (`izipay()`), pero un comentario en
    `pay()` (flujo MercadoPago, dev-only) lo documenta como "MercadoPago" — resto de un cambio de
    pasarela de pago sin actualizar comentarios/nombres.
13. `OrderController::pay()` (GET, marcado "solo para desarrollo") tiene un **token de acceso de
    MercadoPago hardcodeado en el código fuente**.
14. `WebhooksController` está 100% comentado (sin métodos activos) pero su ruta (`POST webhooks`)
    sigue registrada con una acción de array inválida (`[WebhooksController::class]`, sin método) —
    casi seguro no funcional. El callback real de pago es `izipay()`, no este webhook.
15. `OrderController::annuled()` no tiene `$this->authorize()` (a diferencia de `show()`), y responde
    con string plano `'ok'` o el mensaje crudo de la excepción — sin contrato JSON ni status code.
16. `OrderController::orderFailure()` retorna `view('welcome')` sin pasar `$categories` — la vista
    itera esa variable sin fallback, probablemente rompe al visitar esta ruta.

**Órdenes (admin):**
17. `StatusOrder::update()`: `if ($this->status > 1 && $this->status > 5)` es lógicamente
    `$this->status > 5`, **nunca verdadero** — marcar una orden RECIBIDO/ENVIADO/ENTREGADO desde el
    admin nunca marca el `Payment` como APROBADO.
18. `StatusOrder::update()` no restaura stock ni usa transacción al anular (a diferencia del
    `annuled()` del lado cliente) — anular desde el admin deja el stock sin restaurar y sin nota de
    observación (el campo no está en el formulario).
19. `Order::scopeOtherContact/OtherPhone/OtherDocnumber` consultan las columnas equivocadas (copiadas
    de los scopes sin "Other").
20. `ShowOrders`: filtro de status combinado con búsqueda tiene un bug de precedencia SQL (OR/AND sin
    agrupar) que puede filtrar resultados de status incorrecto; los scopes de teléfono/DNI existen
    pero están comentados, no se pueden usar desde la UI.
21. `Order::department()/city()/district()` son relaciones muertas — no existen esas columnas FK en
    `orders`; la ubicación real vive en el JSON `envio` + `envio_type`.

**Catálogo:**
22. `ProductController::files()` (ruta `admin.products.files`) llama `$product->images()`, pero
    `Product` solo define `images_morph()` — probablemente rota; no está referenciada desde ninguna
    vista admin actual (todo upload real pasa por `GalleryImagesProducts`).
23. No existe UI de creación/programación de `FlashOffer` en todo el admin — solo un toggle de
    `status` sobre una oferta ya existente. Hay que diseñarlo de cero en la reescritura.
24. `EditProduct` no permite editar `quantity` del producto base después de crearlo (sin campo, sin
    regla) — solo se puede establecer al crear.
25. `BrandComponent::update()` valida unicidad con `unique:brands,name,'.$this->brand->name` — debería
    ser `$this->brand->id`; puede rechazar guardar una marca sin cambios como "duplicada de sí misma".
26. Tres vocabularios de estado distintos conviven sin normalizar: Producto
    (`BORRADOR=1`/`PUBLICADO=2`), Categoría/Subcategoría (`NO_PUBLIC=0`/`PUBLIC=1`),
    Variante/OfertaFlash (booleano plano) — cada uno con su propio componente de toggle.
27. `Subcategory.keywords` es JSON sin `$casts` (a diferencia de `images` en Producto/Variante, que sí
    usa `array`) — cada lectura/escritura hace `json_encode`/`json_decode` manual.

**Otros:**
28. Migración `create_banners_table` verifica `Schema::hasTable('settings')` en vez de `'banners'` —
    en una migración desde cero, la tabla `banners` no se crearía.
29. Migración `create_districts_table` tiene el `Schema::create` comentado — el origen real de la
    tabla `districts` en producción no es reconstruible solo desde las migraciones.
30. `Province::$fillable` tiene un typo: `deparment_id` en vez de `department_id`.
31. `CategoryController::show()` no filtra por `Category::PUBLIC` (a diferencia de Home y
    `CategoryFilter`) — una categoría no pública sigue siendo visible si se conoce el slug.
32. `CategoryFilter` pagina manualmente en PHP (`->get()` + `sortBy()` + `slice()`), sin
    `LIMIT`/`OFFSET` real — riesgo de escalabilidad en categorías grandes. Su lógica de
    `card_min_price`/`card_has_variants` está duplicada byte-a-byte en `CategoryProducts`.
33. `CategoryFilter::mount()` arma una URL canónica de SEO que incluye los query params de
    filtro/paginación actuales — antipatrón de contenido duplicado para buscadores.
34. La lógica "admin → panel admin, cliente → home" tras login está duplicada en
    `FortifyServiceProvider` y `RedirectIfAuthenticated` en vez de centralizada.
35. `ContactForm`/`ComplaintsBook` envían correo síncrono (`Mail::send`, no `queue`) y no tienen
    protección anti-spam (sin honeypot/captcha/throttle).

---

## 7. Consideraciones para una futura migración a Next.js/Nuxt.js

Con todo lo anterior mapeado, algunas conclusiones concretas para decidir el enfoque:

**A favor de Laravel-como-API (recomendado, ver conversación previa):** la lógica de dominio real —
cálculo de precio con variantes/ofertas (`applyOffer`, `getMinPrice`), validación de stock,
elegibilidad de cupones (`CouponService`), resolución de costo de envío por zona/distrito, generación
cartesiana de variantes — está concentrada en un número relativamente pequeño de clases PHP
(`app/helpers.php`, `CouponService`, unos pocos métodos de `Product`/modelos). Es la parte más cara de
volver a acertar y ya está (mayormente) probada. Reescribirla desde cero en TypeScript duplica ese
riesgo sin necesidad.

**Lo que NO se puede portar 1:1, hay que rediseñar la interacción:**
- Todo el patrón "Livewire escucha un evento, re-renderiza server-side" (`$listeners`,
  `$this->emit()`/`emitTo()`) no tiene equivalente directo — se convierte en llamadas a API +
  estado de cliente (React/Vue) explícito. Los flujos más afectados: carrito (`AddCartItem` ↔
  `DropdownCart`/`CartMobil` vía eventos), cupón (`ApplyCoupon` → `CreateOrder` vía `couponApplied`),
  Home (`CategoryProducts` emitiendo `glider` para inicializar el carrusel).
- Los tres sliders del detalle de producto (Swiper/FlexSlider/Glider.js, ver
  `docs/product-view.md`) están acoplados a jQuery + eventos Livewire — en Next/Nuxt esto se
  resuelve con un solo componente de carrusel React/Vue nativo, no hace falta portar las tres
  librerías.

**Antes de empezar a construir la API, vale la pena resolver (no solo documentar) estos puntos de la
sección 6.2**, porque afectan directamente el contrato de API que se va a diseñar:
- El bug de `izipay()` (#9) y la falta de verificación de firma (#10) — son de integridad de pagos,
  no cosméticos.
- Los tres vocabularios de estado (#26) — normalizarlos a uno solo antes de exponerlos como enum en
  una API es mucho más barato que mantener la triple convención en el nuevo frontend.
- El modelo de envío (`Order` sin FKs reales, todo en JSON — #21) — si la API va a exponer
  direcciones de envío estructuradas, este es el momento de decidir si se migra a columnas reales o
  se mantiene como JSON en el contrato de API.

**Fuera de alcance de este documento, pendiente de decisión del equipo:** si el rewrite es solo el
storefront (cliente) o también el panel admin — el panel admin tiene una superficie más chica
(4 grupos, ~12 pantallas) pero con más deuda técnica puntual encontrada (sección 2), mientras que el
storefront tiene menos bugs pero más superficie de interacción en tiempo real a rediseñar.
