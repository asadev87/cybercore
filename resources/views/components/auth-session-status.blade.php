@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-success/30 bg-success/10 px-4 py-2 text-sm font-medium text-success']) }}>
        {{ $status }}
    </div>
@endif
