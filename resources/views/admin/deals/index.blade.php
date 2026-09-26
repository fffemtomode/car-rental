<x-app-layout>
    <div class="max-w-6xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Усі заявки</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4">
            <span class="text-gray-600 text-sm">Загальна вартість підтверджених/завершених угод:</span>
            <span class="text-xl font-bold text-blue-700 ml-2">{{ number_format($totalConfirmedValue, 0, '', ' ') }} грн</span>
        </div>

        <form method="GET" class="mb-4 flex flex-wrap gap-3">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">Усі статуси</option>
                <option value="pending" @selected(request('status') === 'pending')>Очікує підтвердження</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Підтверджено</option>
                <option value="completed" @selected(request('status') === 'completed')>Завершено</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Скасовано</option>
            </select>

            <select name="type" class="border rounded px-3 py-2">
                <option value="">Усі типи</option>
                <option value="rental" @selected(request('type') === 'rental')>Оренда</option>
                <option value="buyout" @selected(request('type') === 'buyout')>Викуп</option>
                <option value="leasing" @selected(request('type') === 'leasing')>Лізинг</option>
            </select>

            <select name="car_id" class="border rounded px-3 py-2">
                <option value="">Усі авто</option>
                @foreach ($cars as $carOption)
                    <option value="{{ $carOption->id }}" @selected((string) request('car_id') === (string) $carOption->id)>{{ $carOption->brand }} {{ $carOption->model }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Фільтрувати</button>
            @if (request()->hasAny(['status', 'type', 'car_id']))
                <a href="{{ route('admin.deals.index') }}" class="text-gray-600 px-4 py-2">Скинути</a>
            @endif
        </form>

        <table class="w-full border text-gray-900">
            <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left p-2">Дата</th>
                <th class="text-left p-2">Клієнт</th>
                <th class="text-left p-2">Телефон</th>
                <th class="text-left p-2">Авто</th>
                <th class="text-left p-2">Тип</th>
                <th class="text-left p-2">Вартість</th>
                <th class="text-left p-2">Статус</th>
                <th class="text-left p-2"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($deals as $deal)
                <tr class="border-b">
                    <td class="p-2 text-sm">{{ $deal->created_at->format('d.m.Y') }}</td>
                    <td class="p-2">{{ $deal->user->name }}</td>
                    <td class="p-2 text-sm">{{ $deal->user->phone ?? '—' }}</td>
                    <td class="p-2">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                    <td class="p-2">{{ $deal->type_label }}</td>
                    <td class="p-2">{{ $deal->total_price ? number_format($deal->total_price, 0, '', ' ') . ' грн' : '—' }}</td>
                    <td class="p-2">{{ $deal->status_label }}</td>
                    <td class="p-2">
                        <a href="{{ route('deals.show', $deal) }}" class="text-blue-600">Деталі</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $deals->links() }}</div>
    </div>
</x-app-layout>
