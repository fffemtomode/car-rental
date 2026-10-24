@php
    $initial = mb_strtoupper(mb_substr($name ?? '?', 0, 1));
    $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-amber-500', 'bg-pink-500', 'bg-teal-500'];
    $color = $colors[array_sum(array_map('ord', str_split($name ?? '?'))) % count($colors)];
@endphp
<div class="{{ $color }} text-white rounded-full flex items-center justify-center font-semibold shrink-0 {{ $size ?? 'w-9 h-9 text-sm' }}">
    {{ $initial }}
</div>
