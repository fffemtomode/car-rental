<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h1>

        @if ($car->photo)
            <img src="{{ Storage::url($car->photo) }}" class="w-full max-h-80 object-cover rounded mb-4">
        @endif

        @if ($car->engine)
            <p class="mb-2 text-gray-900">Двигун: {{ $car->engine }}</p>
        @endif
        @if ($car->mileage)
            <p class="mb-2 text-gray-900">Пробіг: {{ number_format($car->mileage, 0, '', ' ') }} км</p>
        @endif

        <p class="mb-2">Ціна оренди: <strong>{{ $car->price_per_day }} грн/добу</strong></p>
        @if ($car->buyout_price)
            <p class="mb-2">Ціна викупу: <strong>{{ $car->buyout_price }} грн</strong></p>
        @endif
        <p class="mb-4">Статус: {{ $car->status_label }}</p>

        <div class="flex gap-3 mb-6">
            <a href="{{ route('deals.create-rental', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Орендувати</a>
            <a href="{{ route('deals.create-buyout', $car) }}" class="bg-green-600 text-white px-4 py-2 rounded">Викупити</a>
            <a href="{{ route('deals.create-leasing', $car) }}" class="bg-purple-600 text-white px-4 py-2 rounded">Лізинг</a>
        </div>

        @php
            $periods = $car->deals()->where('type', 'rental')->whereIn('status', ['pending', 'confirmed'])->get();
            $dateStatus = [];
            foreach ($periods as $deal) {
                $period = \Carbon\CarbonPeriod::create($deal->start_date, $deal->end_date);
                foreach ($period as $date) {
                    $dateStatus[$date->format('Y-m-d')] = $deal->user_id === auth()->id() ? 'mine' : 'other';
                }
            }
            $now = \Carbon\Carbon::now();
        @endphp
        <h2 class="text-xl font-semibold mt-6 mb-3 text-gray-900">Доступність</h2>
        <div class="flex gap-4 mb-2 text-sm text-gray-600">
            <span><span class="inline-block w-3 h-3 bg-green-50 border border-green-200 rounded-sm"></span> вільно</span>
            <span><span class="inline-block w-3 h-3 bg-blue-100 rounded-sm"></span> моє бронювання</span>
            <span><span class="inline-block w-3 h-3 bg-red-100 rounded-sm"></span> зайнято іншим</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @for ($i = 0; $i < 3; $i++)
                @php $m = $now->copy()->addMonths($i); @endphp
                @include('partials.calendar-month', ['year' => $m->year, 'month' => $m->month, 'dateStatus' => $dateStatus])
            @endfor
        </div>
    </div>
</x-app-layout>
