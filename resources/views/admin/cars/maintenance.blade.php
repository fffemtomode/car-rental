<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">ТО: {{ $car->brand }} {{ $car->model }}</h1>
        <a href="{{ route('admin.cars.index') }}" class="text-blue-600 text-sm">← До списку авто</a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded my-4">{{ session('success') }}</div>
        @endif

        <div class="mt-6 border rounded-lg p-4">
            <h2 class="font-semibold mb-3 text-gray-900">Додати запис</h2>
            <form method="POST" action="{{ route('admin.cars.maintenance.store', $car) }}">
                @csrf
                <div class="flex flex-col gap-3 mb-3">
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Тип робіт</label>
                        <input type="text" name="type" placeholder="Заміна масла, шини..." class="border rounded px-3 py-2 w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Дата</label>
                        <input type="date" name="date" class="border rounded px-3 py-2 w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Пробіг на момент ТО (км)</label>
                        <input type="number" name="mileage_at_service" class="border rounded px-3 py-2 w-full">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-900">Примітка</label>
                        <input type="text" name="note" class="border rounded px-3 py-2 w-full">
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Додати запис</button>
            </form>
        </div>

        <h2 class="font-semibold mt-6 mb-3 text-gray-900">Історія ТО</h2>
        @if ($logs->isEmpty())
            <p class="text-gray-600">Записів ще немає.</p>
        @else
            <table class="w-full border text-gray-900">
                <thead>
                <tr class="border-b bg-gray-100">
                    <th class="text-left p-2">Дата</th>
                    <th class="text-left p-2">Тип робіт</th>
                    <th class="text-left p-2">Пробіг</th>
                    <th class="text-left p-2">Примітка</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($logs as $log)
                    <tr class="border-b">
                        <td class="p-2">{{ $log->date }}</td>
                        <td class="p-2">{{ $log->type }}</td>
                        <td class="p-2">{{ $log->mileage_at_service ? number_format($log->mileage_at_service, 0, '', ' ') . ' км' : '—' }}</td>
                        <td class="p-2">{{ $log->note }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
