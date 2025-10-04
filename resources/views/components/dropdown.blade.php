@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'p-2 space-y-1 rounded-xl border border-border/60 bg-card/95 shadow-lg ring-1 ring-black/5 backdrop-blur-sm'])

@php
 = match () {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

 = match () {
    '48' => 'w-48',
    default => ,
};
@endphp

<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">
    <div @click="open = ! open" class="cursor-pointer">
        {{  }}
    </div>

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 {{  }} {{  }}"
        style="display: none;"
    >
        <div class="{{  }}" @click="open = false">
            {{  }}
        </div>
    </div>
</div>
