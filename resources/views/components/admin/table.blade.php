<div class="admin-table-wrap overflow-x-auto overflow-y-hidden">
    <table {{ $attributes->merge(['class' => 'admin-table w-full min-w-max text-sm text-slate-700 dark:text-slate-200']) }}>
        {{ $slot }}
    </table>
</div>
