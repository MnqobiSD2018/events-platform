@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-teal-600 bg-teal-50 py-2 ps-3 pe-4 text-start text-base font-semibold text-teal-800 transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent py-2 ps-3 pe-4 text-start text-base font-medium text-slate-600 transition duration-150 ease-in-out hover:border-teal-200 hover:bg-slate-50 hover:text-teal-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
