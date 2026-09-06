@props(['type' => 'info', 'message' => ''])

@php
    $types = [
        'info' => ['wrapper' => 'border-sky-200 bg-white text-sky-800 dark:border-sky-900/70 dark:bg-slate-900 dark:text-sky-200', 'accent' => 'bg-sky-500', 'icon' => 'bg-sky-100 text-sky-600 dark:bg-sky-950 dark:text-sky-300', 'close' => 'hover:bg-sky-50 hover:text-sky-700 dark:hover:bg-sky-950/60 dark:hover:text-sky-200', 'iconName' => 'fa-circle-info'],
        'success' => ['wrapper' => 'border-emerald-200 bg-white text-emerald-800 dark:border-emerald-900/70 dark:bg-slate-900 dark:text-emerald-200', 'accent' => 'bg-emerald-600', 'icon' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300', 'close' => 'hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-emerald-950/60 dark:hover:text-emerald-200', 'iconName' => 'fa-check'],
        'warning' => ['wrapper' => 'border-amber-200 bg-white text-amber-800 dark:border-amber-900/70 dark:bg-slate-900 dark:text-amber-200', 'accent' => 'bg-amber-500', 'icon' => 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300', 'close' => 'hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-950/60 dark:hover:text-amber-200', 'iconName' => 'fa-exclamation'],
        'error' => ['wrapper' => 'border-rose-200 bg-white text-rose-800 dark:border-rose-900/70 dark:bg-slate-900 dark:text-rose-200', 'accent' => 'bg-rose-500', 'icon' => 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300', 'close' => 'hover:bg-rose-50 hover:text-rose-700 dark:hover:bg-rose-950/60 dark:hover:text-rose-200', 'iconName' => 'fa-xmark'],
    ];
    $style = $types[$type] ?? $types['info'];
@endphp

@if($message)
    <div role="alert" aria-live="polite" class="relative isolate flex items-start gap-3 overflow-hidden rounded-2xl border px-4 py-3.5 shadow-sm {{ $style['wrapper'] }} animate-fade-in">
        <span class="absolute inset-y-0 left-0 w-1 {{ $style['accent'] }}" aria-hidden="true"></span>
        <span class="ml-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $style['icon'] }}">
            <i class="fas {{ $style['iconName'] }} text-xs"></i>
        </span>
        <p class="min-w-0 flex-1 pt-1 text-sm font-semibold leading-5">{{ $message }}</p>
        <button type="button" onclick="this.closest('[role=alert]').remove()" aria-label="Tutup notifikasi" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors {{ $style['close'] }}">
            <i class="fas fa-xmark text-xs"></i>
        </button>
    </div>
@endif
