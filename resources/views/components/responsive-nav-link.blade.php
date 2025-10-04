@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex w-full items-center gap-2 rounded-xl bg-secondary px-4 py-2 text-base font-semibold text-foreground shadow-sm transition'
            : 'flex w-full items-center gap-2 rounded-xl px-4 py-2 text-base font-medium text-muted-foreground transition hover:bg-secondary hover:text-foreground';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
