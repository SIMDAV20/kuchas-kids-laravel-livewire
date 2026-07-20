---
trigger: always_on
---

# Ecommerce Project — AI Assistant Rules

## Project Context
- **Location**: Laragon `www` folder (e.g., `C:/laragon/www/kuchas-kids`)
- **Stack**: Laravel 10, Livewire 2, Alpine.js
- **Styling**: Tailwind CSS (custom colors defined in `tailwind.config.js`)
- **Environment**: Local development via Laragon (Apache/Nginx + MariaDB + PHP 8.1)

---

## General Rules

- Always follow **Laravel 9** conventions (not Laravel 10+). Use `Route::`, 
  `$this->middleware()`, and other L9-compatible syntax.
- Use **Livewire 2** syntax exclusively. Do NOT use Livewire 3 syntax 
  (e.g., no `#[Attribute]` PHP attributes, no `wire:navigate`, no `volt` components).
- Use **Alpine.js** for lightweight client-side interactivity. Prefer Alpine over 
  writing custom vanilla JS or jQuery unless a third-party plugin requires it.
- Use **Tailwind CSS utility classes** for all styling. Do NOT write custom CSS 
  unless absolutely necessary.
- Always reference **custom colors from `tailwind.config.js`** instead of 
  hardcoding hex values or using default Tailwind color names that may conflict.

---

## File & Folder Conventions

- Controllers go in `app/Http/Controllers/`
- Livewire components go in `app/Http/Livewire/` (class) and `resources/views/livewire/` (blade)
- Models go in `app/Models/`
- Blade layouts go in `resources/views/layouts/`
- Blade components go in `resources/views/components/`
- Config files go in `config/`
- Migrations go in `database/migrations/`

---

## Livewire 2 Rules

- Livewire component classes must extend `Livewire\Component`
- Use `wire:model` for two-way data binding (not `wire:model.live` — that's Livewire 3)
- Use `wire:model.defer` when you want to defer syncing until a form submit
- Use `wire:click`, `wire:submit.prevent`, `wire:loading`, `wire:dirty` as needed
- Emit events with `$this->emit('eventName', $data)` (not `dispatch()` — that's Livewire 3)
- Listen to events with `protected $listeners = ['eventName' => 'methodName']`
- Use `protected $rules` for validation or call `$this->validate()` inline
- Pagination: use `WithPagination` trait; in Blade use `{{ $items->links() }}`

---

## Alpine.js Rules

- Initialize components with `x-data`
- Use `x-show`, `x-if`, `x-bind`, `x-on`, `x-text`, `x-model` for reactivity
- For communication between Alpine and Livewire, use:
  - `$wire.methodName()` to call Livewire methods from Alpine
  - `@this.on('eventName', callback)` to listen to Livewire events in Alpine
- Avoid mixing Alpine state with Livewire state unless necessary
- **Inline `x-data`** is fine for simple reactive state (e.g. `x-data="{ open: false }"`).
- **Complex or reusable Alpine logic** (more than ~3 lines, or used in multiple places) must be
  extracted to `Alpine.data()` and registered inside the `alpine:init` event in `@push('scripts')`:
  ```js
  document.addEventListener('alpine:init', () => {
      Alpine.data('myComponent', () => ({
          // logic here
      }))
  })
  ```
  This prevents race conditions where Alpine has already started before the definition is registered.

---

## Tailwind CSS Rules

- Always check `tailwind.config.js` for custom color tokens (e.g., `primary`, `secondary`, `accent`) before using generic Tailwind colors
- Use responsive prefixes (`sm:`, `md:`, `lg:`, `xl:`) for layout breakpoints
- Use `@apply` in component CSS only when a pattern repeats frequently
- Do NOT use arbitrary values (e.g., `w-[347px]`) unless there is no utility equivalent

---

## Plugin Awareness

- This project uses multiple third-party plugins. Before adding a new plugin, check if one already exists for the same purpose in `package.json` or `composer.json`
- For JS plugins, prefer initializing them inside `Alpine.data()` or in a dedicated JS module
- For PHP/Laravel plugins, always check if a Service Provider needs to be registered in `config/app.php`

---

## Database & Eloquent Rules

- Use Eloquent ORM; avoid raw SQL unless performance demands it
- Define relationships in models (`hasMany`, `belongsTo`, `belongsToMany`, etc.)
- Always use **migrations** for schema changes — do NOT edit the DB directly
- Use `$fillable` or `$guarded` in every model
- Soft deletes: use `SoftDeletes` trait when records should be recoverable

---

## Blade Templating Rules

- Extend layouts with `@extends('layouts.app')` and `@section` / `@yield`
- Use Blade components with `<x-component-name />` syntax
- Use `@livewire('component-name')` or `<livewire:component-name />` to embed Livewire
- Escape output with `{{ }}` always; use `{!! !!}` only for trusted HTML

---

## Security Rules

- Always validate and sanitize input in Livewire/Controller methods
- Use Laravel's CSRF protection (`@csrf`) in all standard forms
- Never expose sensitive `.env` values in Blade or JS
- Use Gates and Policies for authorization logic

---

## Laragon-Specific Notes

- The local dev URL is typically `http://<project-folder>.test`
- `.env` should have `APP_URL=http://<project-folder>.test`
- MySQL runs on port `3306` by default in Laragon
- Run `php artisan` commands via Laragon's terminal or any terminal pointed to the project root
