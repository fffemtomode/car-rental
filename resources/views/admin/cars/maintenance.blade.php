<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">ТО: {{ $car->brand }} {{ $car->model }}</h1>
        <a href="{{ route('admin.cars.index') }}" class="text-blue-600 text-sm">← До списку авто</a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg my-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mt-6">
            <h2 class="font-semibold mb-3 text-gray-900">Додати запис</h2>
            <form method="POST" action="{{ route('admin.cars.maintenance.store', $car) }}">
                @csrf
                <div class="flex flex-col gap-3 mb-3">
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Тип робіт</label>
                        <input type="text" name="type" placeholder="Заміна масла, шини..." class="border border-gray-300 rounded-lg px-3 py-2 w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Дата</label>
                        <input type="date" name="date" class="border border-gray-300 rounded-lg px-3 py-2 w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Пробіг на момент ТО (км)</label>
                        <input type="number" name="mileage_at_service" class="border border-gray-300 rounded-lg px-3 py-2 w-full">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Примітка</label>
                        <input type="text" name="note" class="border border-gray-300 rounded-lg px-3 py-2 w-full">
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Додати запис</button>
            </form>
        </div>

        <h2 class="font-semibold mt-6 mb-3 text-gray-900">Історія ТО</h2>
        @if ($logs->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl p-8 text-center text-gray-500">Записів ще немає.</div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-gray-900">
                    <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Дата</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Тип робіт</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Пробіг</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Примітка</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="px-4 py-3">
                                {{ $log->date }}
                                <div class="text-xs text-gray-400">додано {{ $log->created_at->format('d.m.Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $log->type }}</td>
                            <td class="px-4 py-3">{{ $log->mileage_at_service ? number_format($log->mileage_at_service, 0, '', ' ') . ' км' : '—' }}</td>
                            <td class="px-4 py-3">{{ $log->note }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
