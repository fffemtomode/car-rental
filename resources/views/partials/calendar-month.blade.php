@php
    $monthsUk = ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'];
    $firstDay = \Carbon\Carbon::create($year, $month, 1);
    $daysInMonth = $firstDay->daysInMonth;
    $startWeekday = $firstDay->dayOfWeekIso;
@endphp
<div class="border rounded-lg p-4">
    <h3 class="font-semibold mb-3 text-gray-900 text-center">{{ $monthsUk[$month - 1] }} {{ $year }}</h3>
    <div class="grid grid-cols-7 gap-1 text-xs text-center text-gray-500 mb-1">
        <div>Пн</div><div>Вт</div><div>Ср</div><div>Чт</div><div>Пт</div><div>Сб</div><div>Нд</div>
    </div>
    <div class="grid grid-cols-7 gap-1">
        @for ($i = 1; $i < $startWeekday; $i++)
            <div></div>
        @endfor
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $status = $dateStatus[$dateStr] ?? null;
                $classes = match ($status) {
                    'mine' => 'bg-blue-100 text-blue-700',
                    'other' => 'bg-red-100 text-red-700',
                    default => 'bg-green-50 text-green-700',
                };
            @endphp
            <div class="aspect-square flex items-center justify-center rounded text-sm {{ $classes }}">
                {{ $day }}
            </div>
        @endfor
    </div>
</div>
