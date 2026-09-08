<div class="admin-table-wrap">
    <table {{ $attributes->merge(['class' => 'admin-table w-full min-w-[680px] text-sm text-slate-700 dark:text-slate-200']) }}>
        {{ $slot }}
    </table>
</div>
