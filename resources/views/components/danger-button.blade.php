<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-red-600 px-3.5 py-2 text-sm font-semibold leading-5 text-white transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
