@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-t-lg border-b-2 border-teal-600 px-2 pt-1 text-sm font-semibold leading-5 text-slate-900 transition duration-150 ease-in-out'
            : 'inline-flex items-center rounded-t-lg border-b-2 border-transparent px-2 pt-1 text-sm font-medium leading-5 text-slate-500 transition duration-150 ease-in-out hover:text-teal-700 hover:border-teal-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
