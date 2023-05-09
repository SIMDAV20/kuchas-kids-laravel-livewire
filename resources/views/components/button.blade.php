@props(['color' => 'gray', 'number' => 500])

{{-- reemplazar las comillas simples por dobles para que reconozca el prop $color --}}
<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => "inline-flex justify-center items-center px-4 py-2 bg-$color-$number border border-transparent rounded-md font-semibold text-xs text-white uppercase
                tracking-widest hover:bg-$color-700 active:bg-$color-900 focus:outline-none focus:border-$color-900 focus:ring focus:ring-$color-300 disabled:opacity-50 transition",
    ]) }}>
    {{ $slot }}
</button>
