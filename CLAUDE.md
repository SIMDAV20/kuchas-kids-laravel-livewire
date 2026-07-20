# Kuchas Kids

## Summary

Kuchas Kids is a Spanish-language e-commerce storefront for children's products (clothing, toys,
feeding accessories) plus its backoffice admin panel — a monolithic Laravel 9-style / Livewire 2
application (see stack constraints below). Core storefront flows: catalog browsing with
variant/attribute-based products (color, size, etc.), search, cart, coupon-based discounts, checkout,
and payment via Izipay (card) or Yape (manual receipt upload). The admin panel (role-gated via Spatie
permissions) covers catalog management (products/variants/categories/brands/attributes), sales
(orders/coupons/delivery zones), content (banners), and site administration (users/settings). For
setup/dev commands see `README.md`; for deeper module-by-module documentation see `docs/` (start with
`docs/ecommerce-technical-processes.md`).

## Stack constraints (hard requirements)

This is a **Laravel 9-style / Livewire 2** codebase — `composer.json` says Laravel `^10.0` but the project must be treated as **Laravel 9-compatible syntax**, and Livewire is pinned to `^2.11`. This is a deliberate project rule (see `.claude/rules/main-rules.md`), not an oversight:

- Livewire: no `#[Attribute]` PHP attributes, no `wire:navigate`, no Volt. Use `wire:model` (not `.live`), `wire:model.defer`, `$this->emit()`/`protected $listeners` (not `dispatch()`).
- Alpine.js for client interactivity; extract non-trivial logic to `Alpine.data()` registered on `alpine:init` inside `@push('scripts')` (avoids race conditions with inline `x-data`).
- **Never wrap an element that has `wire:click`/`wire:model` inside Alpine `x-if`.** Alpine destroys/recreates the DOM node on toggle, which detaches Livewire's event listener — the click silently does nothing. Use `x-show` (or a Blade `@if`) instead. This has caused real, hard-to-spot bugs in this codebase (e.g. a coupon "Aplicar" button that looked fine but never fired).
- Tailwind for all styling; check `tailwind.config.js` for custom color tokens before using generic colors; avoid arbitrary values (`w-[347px]`) when a utility exists.
- Full rules (Livewire/Alpine/Blade/Eloquent/security conventions) live in `.claude/rules/main-rules.md` — read it, it is authoritative for coding style in this repo.

## Architecture

### Routing: three route files, merged by a custom RouteServiceProvider

`app/Providers/RouteServiceProvider.php` overrides `map()` to load three separate route files (this is non-default Laravel wiring, easy to miss):

- `routes/web.php` — public storefront (`web` middleware only)
- `routes/admin.php` — prefixed `admin/`, gated by `['web', 'auth', 'role:admin,web']` (Spatie permission role check)
- `routes/api.php` — prefixed `api/`, `api` middleware

When adding admin-only routes/pages, add them to `routes/admin.php`; they're automatically prefixed and role-gated — don't re-add `admin/` or auth middleware manually.

### Livewire component split: public vs admin

- `app/Http/Livewire/*` — storefront components (cart, checkout, product detail, search, coupons, etc.), views in `resources/views/livewire/*`.
- `app/Http/Livewire/Admin/*` — backoffice CRUD components (products, categories, brands, orders, coupons, users, banners, delivery zones), views in `resources/views/livewire/admin/*`.

Some admin flows use a thin controller + Livewire pair (e.g. `Admin/ProductController::files` for file upload alongside the `EditProduct` Livewire component) rather than pure Livewire — check the relevant `Admin/*Controller.php` before assuming a feature is Livewire-only.

Admin Livewire views can define `<x-slot name="header">...</x-slot>` for a page title bar — `layouts/admin.blade.php` renders it via `@isset($header)`. Without that slot the page just has no visible title, so add one when creating a new admin list/edit screen rather than inventing an ad-hoc heading pattern.

### Cart & checkout flow

- Cart state lives in `hardevine/shoppingcart` (`Gloudemans\Shoppingcart\Facades\Cart`), not a custom DB table — session/DB-backed depending on package config.
- Stock is tracked either on `Product.quantity` directly, or per `ProductVariant.stock` when a product has variants (`Product::getStockAttribute()` sums variant stock if variants exist, else falls back to `quantity`).
- `app/helpers.php` (autoloaded globally via `composer.json` `autoload.files`) provides cart/stock helpers used across Blade/Livewire: `current_quantity()`, `qty_added()`, `qty_available()`, `discount()` (decrements stock on order placement — variant-aware).
- Checkout: `Livewire\CreateOrder` → `Order` model → `Livewire\PaymentOrder` → `OrderController::izipay()` handles the Izipay/PayZen gateway callback directly (`POST orders/izipay`, via `lyracom/rest-php-sdk`, CSRF-exempt). There is **no** separate webhook controller — a `WebhooksController`/`POST webhooks` route existed but was 100% dead code (commented-out handler, malformed route registration) and was removed; don't reintroduce it as the "real" callback path.
- `CreateOrder::create_order()` re-validates every cart line against live stock (`current_quantity()`) right before placing the order, in addition to the client-side clamps in `AddCartItem`/`UpdateCartItem` — don't assume the UI-disabled state is the only guard when touching quantity logic.
- Coupons (`app/Models/Coupon.php`, `app/Services/CouponService.php`) apply to cart contents post-hoc: types are `category`, `product_group`, `shipping`, each with its own eligibility + discount calculation. `ApplyCoupon`/`Admin/CouponComponent` are the Livewire entry points. `ApplyCoupon::apply()` rejects re-applying while a coupon is already active (server-side, not just a hidden button) — mirror that pattern (validate server-side, don't rely on `x-bind:disabled` alone) for any similar one-shot action.

### Product/catalog model shape

- `Product belongsTo Brand`, `belongsTo Subcategory`; `Subcategory belongsTo Category`. Coupon category-eligibility checks walk `product->subcategory->category_id`, not a direct `product->category`.
- Products can have `ProductVariant`s (with their own `stock`, `slug`); a legacy polymorphic `images_morph()` relation exists but is superseded by a JSON `images` column (`Product.images`, cast to array) holding an ordered list of `Image` model IDs — use `getAssignedImagesAttribute()` rather than the morph relation for current image logic.
- `App\Traits\ProductScopes` holds query scopes for `Product` — check there before adding a new `Product::where(...)` scope pattern.

### Auth & authorization

- Laravel Jetstream + Fortify for auth scaffolding (registration, 2FA, sessions, API tokens — see `tests/Feature/*` for the Jetstream-provided test suite).
- Spatie `laravel-permission` for roles; admin area is gated by the `role:admin,web` middleware, not a custom `is_admin` flag.

### Localization / SEO

- `laraveles/spanish` provides Spanish Carbon/validation locale — this app is Spanish-first (see Spanish comments throughout controllers/routes).
- `artesaos/seotools` (`SEOTools` facade) is used for meta tags; check `SecondaryPagesController::markdownPage` — informational pages (`info/{page}`) are rendered from Markdown files in `resources/markdown/pages/`, not DB-backed CMS content.

## Further documentation

Deeper technical/process docs live in `docs/` — read `docs/ecommerce-technical-processes.md` first
for a full module-by-module map of the admin panel and storefront (data model, business rules, known
issues); `docs/incidencias.md` logs bugs found and fixed, file by file. Narrower write-ups: 
`docs/product-workflow.md` (attribute/variant admin CRUD), `docs/flow.md` (product/variant ER + 
frontend flow), `docs/product-view.md` (product gallery slider stack), `docs/cart-workflow.md`
(add-to-cart → checkout), `docs/banner-workflow.md`, `docs/upload-fix-php83.md` (local file-upload
environment fix, PHP 8.3 + Apache FastCGI). These are more current/detailed than this file for their
specific areas — check them before re-deriving behavior from scratch.
