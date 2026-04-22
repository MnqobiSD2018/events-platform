<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg border border-teal-700 bg-teal-700 px-4 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-white transition duration-150 ease-in-out hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 active:bg-teal-800']) }}>
    {{ $slot }}
</button>
