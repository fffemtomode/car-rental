<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Оренда: {{ $car->brand }} {{ $car->model }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('deals.store-rental', $car) }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Дата початку</label>
                <input type="date" name="start_date" class="border rounded px-3 py-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Дата завершення</label>
                <input type="date" name="end_date" class="border rounded px-3 py-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Підтвердити заявку</button>
        </form>
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
        <h2 class="text-lg font-semibold mt-8 mb-3 text-gray-900">Доступність</h2>
        <div class="flex gap-4 mb-2 text-sm text-gray-600">
            <span><span class="inline-block w-3 h-3 bg-green-50 border border-green-200 rounded-sm"></span> вільно</span>
            <span><span class="inline-block w-3 h-3 bg-blue-100 rounded-sm"></span> моє бронювання</span>
            <span><span class="inline-block w-3 h-3 bg-red-100 rounded-sm"></span> зайнято іншим</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @for ($i = 0; $i < 2; $i++)
                @php $m = $now->copy()->addMonths($i); @endphp
                @include('partials.calendar-month', ['year' => $m->year, 'month' => $m->month, 'dateStatus' => $dateStatus])
            @endfor
        </div>
    </div>
</x-app-layout>
