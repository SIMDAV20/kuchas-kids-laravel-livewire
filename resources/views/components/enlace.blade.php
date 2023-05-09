@props(['color' => 'red', 'number' => 600])

<a {{ $attributes->merge(['type' => 'button', 'class' => "inline-flex items-center justify-center px-4 py-2 bg-$color-$number border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-$color-500 focus:outline-none focus:border-$color-700 focus:shadow-outline-$color active:bg-$color-600 disabled:opacity-25 transition"]) }}>
    {{ $slot }}
</a>
