@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-slate-300 bg-white/90 shadow-sm focus:border-teal-600 focus:ring-teal-500']) }}>
