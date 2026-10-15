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
            <div class="bg-white border border-gray-200 rounded-xl p-8 text-center text-gray-500">Активних бронювань немає.</div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-gray-900">
                    <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Клієнт</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Період</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($periods as $deal)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="px-4 py-3">{{ $deal->user->name }}</td>
                            <td class="px-4 py-3">{{ $deal->start_date }} — {{ $deal->end_date }}</td>
                            <td class="px-4 py-3">{{ $deal->status_label }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
