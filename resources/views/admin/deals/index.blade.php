<x-app-layout>
    <div class="max-w-6xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Усі заявки</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <span class="text-gray-600 text-sm">Загальна вартість підтверджених/завершених угод:</span>
                <span class="text-xl font-bold text-blue-700 ml-2">{{ number_format($totalConfirmedValue, 0, '', ' ') }} грн</span>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                <span class="text-gray-600 text-sm">Цього місяця: {{ $monthDealsCount }} заявок на суму</span>
                <span class="text-xl font-bold text-green-700 ml-2">{{ number_format($monthDealsValue, 0, '', ' ') }} грн</span>
            </div>
        </div>

        <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Статус</label>
                <select name="status" class="border border-gray-300 rounded-lg pl-3 pr-8 py-2 bg-white text-gray-900 text-sm min-w-[180px]">
                    <option value="">Усі статуси</option>
                    <option value="pending" @selected(request('status') === 'pending')>Очікує підтвердження</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Підтверджено</option>
                    <option value="completed" @selected(request('status') === 'completed')>Завершено</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Скасовано</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Тип</label>
                <select name="type" class="border border-gray-300 rounded-lg pl-3 pr-8 py-2 bg-white text-gray-900 text-sm min-w-[140px]">
                    <option value="">Усі типи</option>
                    <option value="rental" @selected(request('type') === 'rental')>Оренда</option>
                    <option value="buyout" @selected(request('type') === 'buyout')>Викуп</option>
                    <option value="leasing" @selected(request('type') === 'leasing')>Лізинг</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Авто</label>
                <select name="car_id" class="border border-gray-300 rounded-lg pl-3 pr-8 py-2 bg-white text-gray-900 text-sm min-w-[160px]">
                    <option value="">Усі авто</option>
                    @foreach ($cars as $carOption)
                        <option value="{{ $carOption->id }}" @selected((string) request('car_id') === (string) $carOption->id)>{{ $carOption->brand }} {{ $carOption->model }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">З дати</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">По дату</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-sm">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Фільтрувати</button>
            @if (request()->hasAny(['status', 'type', 'car_id', 'date_from', 'date_to']))
                <a href="{{ route('admin.deals.index') }}" class="text-gray-600 px-2 py-2">Скинути</a>
            @endif
            <a href="{{ route('admin.deals.export', request()->query()) }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 ml-auto">⬇ Експорт в Excel</a>
        </form>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-gray-900">
                <thead>
                <tr class="bg-gray-50 border-b-2 border-gray-200">
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Дата</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Клієнт</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Телефон</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Авто</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Тип</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Вартість</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Статус</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($deals as $deal)
                    <tr class="border-b border-gray-100 last:border-0 {{ $deal->status === 'pending' ? 'bg-amber-50 hover:bg-amber-100' : 'hover:bg-gray-50' }}">
                        <td class="px-4 py-3 text-sm">{{ $deal->created_at->format('d.m.Y') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $deal->user->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $deal->user->phone ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                        <td class="px-4 py-3">{{ $deal->type_label }}</td>
                        <td class="px-4 py-3">{{ $deal->total_price ? number_format($deal->total_price, 0, '', ' ') . ' грн' : '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($deal->status === 'pending')
                                <span class="text-xs font-medium bg-amber-200 text-amber-800 px-2 py-1 rounded-full">{{ $deal->status_label }}</span>
                            @else
                                <span class="text-xs font-medium px-2 py-1 rounded-full
                                    {{ $deal->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                                       ($deal->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600') }}">
                                    {{ $deal->status_label }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('deals.show', $deal) }}" class="text-blue-600 hover:underline">Деталі</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $deals->links() }}</div>
    </div>
</x-app-layout>
