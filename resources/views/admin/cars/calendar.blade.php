<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">Календар: {{ $car->brand }} {{ $car->model }}</h1>
        <a href="{{ route('admin.cars.index') }}" class="text-blue-600 text-sm">← До списку авто</a>

        @php
            $dateStatus = [];
            foreach ($bookedDates as $d) {
                $dateStatus[$d] = 'other';
            }
            $now = \Carbon\Carbon::now();
        @endphp

        <div class="flex gap-4 my-4 text-sm text-gray-600">
            <span><span class="inline-block w-3 h-3 bg-green-50 border border-green-200 rounded-sm"></span> вільно</span>
            <span><span class="inline-block w-3 h-3 bg-red-100 rounded-sm"></span> зайнято</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @for ($i = 0; $i < 3; $i++)
                @php $m = $now->copy()->addMonths($i); @endphp
                @include('partials.calendar-month', ['year' => $m->year, 'month' => $m->month, 'dateStatus' => $dateStatus])
            @endfor
        </div>

        <h2 class="font-semibold mb-3 text-gray-900">Заброньовані періоди</h2>
        @if ($periods->isEmpty())
            <p class="text-gray-600">Активних бронювань немає.</p>
        @else
            <table class="w-full border text-gray-900">
                <thead>
                <tr class="border-b bg-gray-100">
                    <th class="text-left p-2">Клієнт</th>
                    <th class="text-left p-2">Період</th>
                    <th class="text-left p-2">Статус</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($periods as $deal)
                    <tr class="border-b">
                        <td class="p-2">{{ $deal->user->name }}</td>
                        <td class="p-2">{{ $deal->start_date }} — {{ $deal->end_date }}</td>
                        <td class="p-2">{{ $deal->status_label }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
